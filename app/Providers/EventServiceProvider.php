<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\BookingCanceled;
use Coderstm\Events\LogCreated;
use App\Events\GuestPassCreated;
use App\Events\UserStatusUpdated;
use App\Listeners\SendBookingCanceled;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ProcessStandbyBooking;
use App\Listeners\SendBookingConfirmation;
use App\Listeners\SendGuestPassNotification;
use App\Listeners\UserStatusUpdatedNotification;
use App\Listeners\SendLogableNotification;
use App\Listeners\CreateEnquiryMemberByGuestPass;
use App\Listeners\Usages\AddClassesFeatureUsages;
use App\Listeners\Usages\AddGuestPassFeatureUsages;
use App\Listeners\Usages\RemoveClassesFeatureUsages;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingCreated::class => [
            SendBookingConfirmation::class,
            AddClassesFeatureUsages::class,
        ],
        BookingCanceled::class => [
            SendBookingCanceled::class,
            ProcessStandbyBooking::class,
            RemoveClassesFeatureUsages::class,
        ],
        GuestPassCreated::class => [
            CreateEnquiryMemberByGuestPass::class,
            SendGuestPassNotification::class,
            AddGuestPassFeatureUsages::class,
        ],
        LogCreated::class => [
            SendLogableNotification::class,
        ],
        UserStatusUpdated::class => [
            UserStatusUpdatedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
