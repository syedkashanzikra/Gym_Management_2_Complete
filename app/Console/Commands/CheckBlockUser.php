<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckBlockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:blocked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check block users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // marked as blocked who have last week no show
        User::whereHas('bookings', function ($q) {
            $q->onlyLastWeekNoShow();
        })->each(function ($user) {
            if ($user->isBlocked() && $user->blocked->disabled) {
                $this->info("User #{$user->id} already blocked until {$user->blocked->release_at}!");
            } else if ($user->last_ns_bookings) {
                $blocked = $user->updateOrCreateBlocked([
                    'release_at' => $user->last_ns_bookings->schedules_at->addDays(3)
                ]);
                $this->info("User #{$user->id} has blocked until {$blocked->release_at}!");
            }
        });

        // remove blocked who doesn't have last week no show
        User::whereDoesntHave('bookings', function ($q) {
            $q->onlyLastWeekNoShow();
        })->has('blocked')->each(function ($user) {
            $user->blocked()->where([
                'type' => 'NS'
            ])->update([
                'disabled' => true,
            ]);
        });
    }
}
