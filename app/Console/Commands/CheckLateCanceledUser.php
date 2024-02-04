<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckLateCanceledUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:late-cancelled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check late cancelled users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // marked as blocked who have last week late cancelled
        User::whereHas('bookings', function ($q) {
            $q->onlyLastWeekLateCancellation();
        }, '>=', 2)->each(function ($user) {
            if ($user->isBlocked() && $user->blocked->disabled) {
                $this->info("User #{$user->id} already blocked until {$user->blocked->release_at}!");
            } else if ($user->last_late_cancellation) {
                $blocked = $user->updateOrCreateBlocked([
                    'type' => 'LC',
                    'release_at' => $user->last_late_cancellation->schedules_at->addDays(3)
                ]);
                $this->info("User #{$user->id} has blocked until {$blocked->release_at}!");
            }
        });

        // remove blocked who doesn't have last week late cancelled
        User::whereDoesntHave('bookings', function ($q) {
            $q->onlyLastWeekLateCancellation();
        })->has('blocked')->each(function ($user) {
            $user->blocked()->where([
                'type' => 'LC'
            ])->update([
                'disabled' => true,
            ]);
        });
    }
}
