/**
 * Sistema de notificaciones en tiempo real con Laravel Reverb
 */

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que el usuario esté autenticado
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    
    if (!userId) {
        console.log('Usuario no autenticado, no se inicializarán las notificaciones en tiempo real');
        return;
    }

    console.log('🔔 Inicializando notificaciones en tiempo real para usuario:', userId);
    console.log('🔌 Estado de Echo:', window.Echo ? 'Disponible' : 'No disponible');

    // Escuchar notificaciones en el canal privado del usuario
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            console.log('✅ Nueva notificación recibida:', notification);
            
            // Mostrar notificación en la UI
            mostrarNotificacion(notification);
            
            // Actualizar contador de notificaciones no leídas
            actualizarContadorNotificaciones();
            
            // Reproducir sonido (opcional)
            reproducirSonidoNotificacion();
        })
        .error((error) => {
            console.error('❌ Error en canal de notificaciones:', error);
        });
    
    console.log('✓ Suscripción a canal completada: private-App.Models.User.' + userId);
});

/**
 * Mostrar notificación en la interfaz
 */
function mostrarNotificacion(notification) {
    const { titulo, mensaje, tipo, url, id } = notification;
    
    // Crear elemento de notificación toast
    const toast = document.createElement('div');
    toast.className = `notification-toast ${tipo || 'info'}`;
    
    // URL de detalle de la notificación
    const detailUrl = id ? `/notifications/${id}` : (url || '#');
    
    toast.innerHTML = `
        <div class="notification-content" style="cursor: pointer;" onclick="window.location.href='${detailUrl}'">
            <div class="notification-header">
                <strong>${titulo || 'Notificación'}</strong>
                <button class="notification-close" onclick="event.stopPropagation(); this.parentElement.parentElement.parentElement.remove()">×</button>
            </div>
            <div class="notification-body">${mensaje || 'Tienes una nueva notificación'}</div>
            <div class="notification-footer" style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                <i class="fas fa-hand-pointer" style="margin-right: 4px;"></i>
                Click para ver detalles
            </div>
        </div>
    `;
    
    // Agregar a la página
    let container = document.getElementById('notifications-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notifications-container';
        container.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    
    // Auto-remover después de 8 segundos (más tiempo para que el usuario pueda hacer click)
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 8000);
}

/**
 * Actualizar contador de notificaciones no leídas
 */
function actualizarContadorNotificaciones() {
    // Buscar el badge en el header
    const badge = document.querySelector('.fa-bell + span') || 
                  document.querySelector('.notification-badge') ||
                  document.querySelector('[class*="badge"]');
    
    if (badge) {
        const count = parseInt(badge.textContent) || 0;
        badge.textContent = count + 1;
        badge.style.display = 'inline-block';
        
        // Añadir animación
        badge.classList.add('animate-pulse');
    } else {
        // Si no existe el badge, crearlo
        const bellIcon = document.querySelector('.fa-bell');
        if (bellIcon) {
            const newBadge = document.createElement('span');
            newBadge.className = 'absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 animate-pulse notification-badge';
            newBadge.textContent = '1';
            bellIcon.parentElement.style.position = 'relative';
            bellIcon.parentElement.appendChild(newBadge);
        }
    }
}

/**
 * Reproducir sonido de notificación
 */
function reproducirSonidoNotificacion() {
    // Opcional: agregar un archivo de sonido
    // const audio = new Audio('/sounds/notification.mp3');
    // audio.play().catch(e => console.log('No se pudo reproducir el sonido:', e));
}

// Estilos CSS inline para las notificaciones toast
const style = document.createElement('style');
style.textContent = `
    .notification-toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-bottom: 10px;
        padding: 16px;
        border-left: 4px solid #3b82f6;
        animation: slideIn 0.3s ease-out;
        transition: opacity 0.3s, transform 0.2s;
    }
    
    .notification-toast:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    
    .notification-content {
        transition: background-color 0.2s;
        border-radius: 4px;
        padding: 4px;
        margin: -4px;
    }
    
    .notification-content:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .notification-toast.success {
        border-left-color: #10b981;
    }
    
    .notification-toast.error {
        border-left-color: #ef4444;
    }
    
    .notification-toast.warning {
        border-left-color: #f59e0b;
    }
    
    .notification-toast.info {
        border-left-color: #3b82f6;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .notification-header strong {
        color: #1f2937;
        font-size: 14px;
    }
    
    .notification-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        line-height: 1;
    }
    
    .notification-close:hover {
        color: #1f2937;
    }
    
    .notification-body {
        color: #4b5563;
        font-size: 13px;
        margin-bottom: 8px;
    }
    
    .notification-link {
        display: inline-block;
        color: #3b82f6;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }
    
    .notification-link:hover {
        text-decoration: underline;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
