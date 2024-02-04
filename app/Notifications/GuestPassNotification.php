<?php

namespace App\Notifications;

use App\Models\GuestPass;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GuestPassNotification extends Notification
{
    use Queueable;

    public $guestPass;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(GuestPass $guestPass)
    {
        $this->guestPass = $guestPass;
        $this->user = $guestPass->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject('New Guest Pass Requested - Action Required')
            ->markdown('emails.admin.guest-pass-request', [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
                'created_at' => $this->guestPass->created_at->format('d M, Y \a\t h:i a'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
