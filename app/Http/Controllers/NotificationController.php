<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario autenticado
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filtrar por leídas/no leídas si se proporciona
        $filter = $request->query('filter', 'all');
        
        $query = $user->notifications();
        
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        // Ordenar por más recientes primero y paginar
        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Contar no leídas
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'currentFilter' => $filter
        ]);
    }
    
    /**
     * Mostrar detalle de una notificación específica
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Buscar la notificación del usuario
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            abort(404, 'Notificación no encontrada');
        }
        
        // Marcar como leída si no lo está
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        
        return view('notifications.show', [
            'notification' => $notification
        ]);
    }
    
    /**
     * Marcar una notificación como leída 
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
