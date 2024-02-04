<?php

namespace App\Notifications;

use Coderstm\Models\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActiveMemberNotification extends Notification
{
    use Queueable;

    public $log;
    public $user;
    public $admin;
    public $subject;
    public $subscription;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Log $log)
    {
        $user = $log->logable;
        $this->log = $log;
        $this->user = $user;
        $this->subscription = $user->subscription();
        $this->admin = optional($log->admin)->name ?? 'System';
        $this->subject = "[{$this->admin}] Member Status Update - Marked as Active";
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
            ->subject($this->subject)
            ->markdown('emails.admin.active-member', [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
                'plan' => optional($this->user->price)->label,
                'price' => $this->subscription ? format_amount(optional($this->subscription->price)->amount * 100) : null,
                'interval' => $this->subscription ? optional($this->subscription->price)->interval : null,
                'note' => $this->user->note,
                'date_time' => $this->log->date_time,
                'admin' => $this->admin,
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
