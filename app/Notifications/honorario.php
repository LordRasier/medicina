<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class honorario extends Notification
{
    use Queueable;
    protected $honorario;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($honorario)
    {
        $this->honorario = $honorario;
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
                    ->line('Se ha cargado un nuevo honorario para usted puede verlo accediendo al siguiente link')
                    ->action('Notification Action', url('http://homestead.medicina/honorario/show/'.$this->honorario))
                    ->line('Gracias por usar nuestra aplicacion');
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
