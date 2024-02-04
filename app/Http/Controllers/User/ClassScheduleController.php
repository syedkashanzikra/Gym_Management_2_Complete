<?php

namespace App\Http\Controllers\User;

use App\Events\BookingCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Http\Controllers\Controller;
use Coderstm\Traits\Helpers;

class ClassScheduleController extends Controller
{
    use Helpers;

    public function index(Request $request, ClassSchedule $classSchedule)
    {
        $startOfWeek = $request->filled('startOfWeek') ? Carbon::parse($request->startOfWeek)->startOfWeek() : now()->startOfWeek();
        $otherSortBy = $request->otherSortBy;

        $classSchedule = $classSchedule->whereBetween('date_at',  [now(), now()->addDays(6)->endOfDay()]);

        if ($request->filled('filter')) {
            $classSchedule->whereHas('class', function ($q) use ($request) {
                $q->where('name', 'like', "{$request->filter}%");
            })->orWhereHas('instructor', function ($q) use ($request) {
                $q->where('name', 'like', "{$request->filter}%");
            });
        }

        $classSchedule->orderByRaw('DATE(date_at)');

        if ($otherSortBy == 'instructor_id') {
            $classSchedule->orderByRaw('(SELECT CONCAT(first_name," ",last_name) FROM admins WHERE admins.id = class_schedules.instructor_id)');
        } else if ($otherSortBy == 'class_id') {
            $classSchedule->orderByRaw('(SELECT name FROM class_lists WHERE class_lists.id = class_schedules.class_id)');
        } else {
            $classSchedule->orderBy($otherSortBy ?? 'start_at', 'asc');
        }

        $classSchedule =  $classSchedule->get()->map(function ($item) use ($request) {
            $item->is_booked = $item->isBooked(current_user()->id);
            return $item;
        });

        $hasNext = $startOfWeek->eq(now()->startOfWeek());

        return response()->json([
            'data' => $classSchedule,
            'totalCost' => $classSchedule->sum('cost'),
            'startOfWeek' => $startOfWeek->format('Y-m-d'),
            'nextOfWeek' => $startOfWeek->addWeek()->format('Y-m-d'),
            'previousOfWeek' => $hasNext ? false : $startOfWeek->subWeeks(2)->format('Y-m-d'),
        ], 200);
    }

    public function book(Request $request, ClassSchedule $classSchedule)
    {
        $user = current_user();

        if ($user->subscription() && !$user->subscription()->canUseFeature('classes')) {
            abort(403, trans('messages.no_booking_credit'));
        }

        if (!$classSchedule->bookable) {
            abort(403, trans('messages.not_bookable'));
        }

        if ($classSchedule->date_at->lt(now())) {
            abort(403, trans('messages.booking_class_started'));
        }

        if ($classSchedule->has_booked) {
            abort(403, trans('messages.booking_standby'));
        }

        if ($user->has_blocked) {
            $release_date = $user->blocked->release_at->format('d/m/Y');
            abort(403, trans('messages.booking_blocked', ['date' => $release_date]));
        }

        $booking = $classSchedule->confirmBooking($user);

        event(new BookingCreated($booking, $booking->is_stand_by));

        return response()->json([
            'message' => trans('messages.class_booked'),
        ], 200);
    }
}
