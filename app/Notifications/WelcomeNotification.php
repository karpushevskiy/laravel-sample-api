<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Welcome Notification
 *
 * @package \App\Notifications
 */
class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable) : array|string
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return CustomMailMessage
     */
    public function toMail($notifiable) : CustomMailMessage
    {
        return (new CustomMailMessage)
            ->subject(__('notifications.welcome.subject'))
            ->greeting(__('notifications.welcome.greeting'))
            ->line(__('notifications.welcome.first_line'))
            ->line(__('notifications.welcome.second_line', [
                'support_email' => config('app.emails.support'),
            ]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) : array
    {
        return [
            //
        ];
    }
}
