<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $vendor;
    public function __construct(Vendor $vendor)
    {
        $this-> vendor=$vendor;
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
        $subject = sprintf('%s: لقد تم انشاء حسابكم في موقع الامامي %s!', config('app.name'), 'sahbi');
        $greeting = sprintf('مرحبا %s!', $notifiable->name);

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line('The introduction to the notification.')
            ->action('Return to the site', url('/login'))
            ->line('Thank you for using our application!')
            ->line('add line')
            ->salutation('Yours Faithfully');
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
