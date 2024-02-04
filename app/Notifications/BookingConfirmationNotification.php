<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmationNotification extends Notification
{
    use Queueable;

    public $booking;
    public $user;
    public $class;
    public $schedule;
    public $message;
    public $subject;
    public $standby;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, bool $standby = false)
    {
        $this->booking = $booking;
        $this->user = $booking->user;
        $this->standby = $standby;
        $this->schedule = $booking->schedule;
        $this->class =  $this->schedule->class;

        if ($this->standby) {
            $this->message = "<p>A space has come available for <strong>{$this->class->name}</strong> on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated} that you were registered as standby, the system has moved you into the class.  If you no longer want to attend this class please cancel online through Members Portal.<br>Enjoy the class</p>";
            $this->subject = "Confirm booking moved from standby for {$this->class->name} on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}";
        } else if ($this->booking->is_stand_by) {
            $this->message = "<p>You are booked on the standby list for <strong>{$this->class->name}</strong> on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}. if a space becomes available the online system will automatically add you to the class and send you a notification. If you no longer wish to remain on standby please contact us.<br>Enjoy the class</p>";
            $this->subject = "Confirmed standby booking for {$this->class->name} on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}";
        } else {
            $this->message = "<p>You are booked for <strong>{$this->class->name}</strong> on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}. If you can't attend please cancel asap via the Members Portal.<br>Enjoy the class</p>";
            $this->subject = "Confirmed booking for {$this->class->name} on {$this->schedule->date_at_formated} at {$this->schedule->start_at_formated}";
        }
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
            ->subject($this->subject)
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
