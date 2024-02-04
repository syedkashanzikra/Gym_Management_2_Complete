<?php

namespace App\Traits;

use App\Models\Booking;
use App\Events\BookingCanceled;
use App\Events\BookingCreated;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Bookingable
{
    /**
     * Get all of the bookings for the ClassSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    /**
     * Get all of the active_bookings for the ClassSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function active_bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id')->onlyActive();
    }

    /**
     * Get all of the stand_by_bookings for the ClassSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stand_by_bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id')->onlyStandBy();
    }

    public function syncBookings(Collection $bookings)
    {
        $active = [];
        $standby = [];
        $canceled = [];

        // delete canceled active bookings
        $this->bookings()->whereNotIn('id', $bookings->pluck('id')->filter())->each(function ($booking) use (&$canceled) {
            $booking->update([
                'canceled_at' => $booking->canceled_at ?? now()
            ]);
            if ($booking->wasChanged('canceled_at')) {
                $canceled[] = $booking->user;
            }
            // send booking canceled notification
            event(new BookingCanceled($booking));
        });

        // create or updated new active bookings
        $bookings->filter(function ($item) {
            return isset($item['user']['id']);
        })->map(function ($item) {
            return (object) $item;
        })->each(function ($item) use (&$active, &$standby) {
            $booking = $this->bookings()->updateOrCreate([
                'id' => optional($item)->id,
            ], [
                'is_stand_by' => $this->isStandby(),
                'attendance' => optional($item)->attendance,
                'status' => optional($item)->status,
                'source' => optional($item)->source,
                'canceled_at' => null,
                'user_id' => optional($item)->user ? optional($item)->user['id'] : null,
            ]);


            if ($booking->wasRecentlyCreated) {
                // send booking confirm notification
                $active[] = $booking->user;
                event(new BookingCreated($booking));
            } else if ($booking->wasChanged('is_stand_by')) {
                if ($booking->is_stand_by) {
                    $standby[] = $booking->user;
                } else {
                    $active[] = $booking->user;
                }
                event(new BookingCreated($booking, !$booking->is_stand_by));
            }
        });

        if (count($active) > 0) {
            $this->createLog($active, 'active');
        }

        if (count($canceled) > 0) {
            $this->createLog($canceled, 'canceled');
        }

        if (count($standby) > 0) {
            $this->createLog($standby, 'standby');
        }

        return $this;
    }

    public function updateStandbyBookings()
    {
        $this->stand_by_bookings()->each(function ($item) {
            if (!$this->isStandby()) {
                $booking = $item->update([
                    'is_stand_by' => false,
                ]);

                if ($booking) {
                    event(new BookingCreated($item, true));
                }
            }
        });

        return $this;
    }

    public function createLog(array $users, $type = 'active')
    {
        $userCount = count($users);
        $userNames = collect($users)->map(function ($user, $index) use ($userCount) {
            if ($userCount > 1 && $index === $userCount - 1) {
                return "and <span class=\"mention\" data-user=\"{$user->id}\">{$user->name}</span>";
            }
            return "<span class=\"mention\" data-user=\"{$user->id}\">{$user->name}</span>";
        })->implode(', ');

        if ($type == 'active') {
            $message = $userNames . " has been added as active booking.";
        } else if ($type == 'standby') {
            $message = $userNames . " has been added as standby booking.";
        } else {
            $message = $userNames . " has been canceled";
        }

        $this->logs()->create([
            'type' => "booking-{$type}",
            'message' => $message,
        ]);
    }
}
