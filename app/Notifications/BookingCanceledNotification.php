<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCanceledNotification extends Notification
{
    use Queueable;

    public $booking;
    public $user;
    public $class;
    public $schedule;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->user = $booking->user;
        $this->schedule = $booking->schedule;
        $this->class =  $this->schedule->class;

        $this->message = "<p>You have canceled booking for <strong>{$this->class->name}</strong> on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}.</p>";
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
            ->greeting("Hi " . optional($this->user)->first_name . ",")
            ->subject("Canceled booking for {$this->class->name} on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}")
            ->line(new HtmlString($this->message))
            ->line('Thank you for using our portal!');
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
