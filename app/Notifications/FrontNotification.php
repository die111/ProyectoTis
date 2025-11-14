<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class FrontNotification extends Notification
{
    use Queueable;

    protected $titulo;
    protected $mensaje;
    protected $tipo;
    protected $url;
    protected $inscriptionId;

    /**
     * Create a new notification instance.
     */
    public function __construct($titulo, $mensaje, $tipo = 'info', $url = null, $inscriptionId = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
        $this->inscriptionId = $inscriptionId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id ?? null, // ID de la notificación 
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'url' => $this->url,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->titulo,    
            'message' => $this->mensaje,   
            'icon' => $this->getIconForType($this->tipo),
            'type' => $this->tipo,
            'route' => $this->url,
            'inscription_id' => $this->inscriptionId,
            'timestamp' => now()->toDateTimeString()
        ];
    }
    
    /**
     * Obtener icono según el tipo de notificación
     */
    private function getIconForType($tipo): string
    {
        return match($tipo) {
            'success' => 'fa-check-circle',
            'error' => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            'info' => 'fa-info-circle',
            default => 'fa-bell',
        };
    }
}
