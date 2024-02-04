<?php

namespace App\Notifications;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewStaffNotification extends Notification
{
    use Queueable;

    public $admin;
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, $password = '********')
    {
        $this->admin = $admin;
        $this->password = $password;
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
            ->subject('Welcome to ' . config('app.name') . ' - Your New Staff Account Details')
            ->markdown('emails.admin.new-account', [
                'name' => $this->admin->first_name,
                'email' => $this->admin->email,
                'password' => $this->password,
                'url' => admin_url('auth/login'),
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
