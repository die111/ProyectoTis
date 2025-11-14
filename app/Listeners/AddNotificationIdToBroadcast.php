<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\BroadcastNotificationCreated;

class AddNotificationIdToBroadcast
{
    /**
     * Handle the event.
     */
    public function handle(BroadcastNotificationCreated $event): void
    {
        // ID de la notificación al payload del broadcast
        $event->data['id'] = $event->notification->id;
    }
}
