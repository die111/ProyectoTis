<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FrontNotification extends Notification
{
    use Queueable;

    protected $titulo;
    protected $mensaje;
    protected $tipo;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($titulo, $mensaje, $tipo = 'info', $url = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'url' => $this->url,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
