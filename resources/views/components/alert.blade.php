@props([
    'type' => 'info',
    'title' => null,
    'text' => null,
    'confirmText' => 'Aceptar',
    'cancelText' => 'Cancelar',
    'showCancel' => false,
    'method' => 'GET',
    'formId' => null,
    'url' => null,
    'icon' => null,
    'iconColor' => null,
    'footer' => null,
    'draggable' => true
])

@php
    // Configuración de iconos por tipo
    switch ($type) {
        case 'success':
            $defaultIcon = 'success';
            $defaultIconColor = '#10B981';
            break;
        case 'error':
        case 'danger':
            $defaultIcon = 'error';
            $defaultIconColor = '#EF4444';
            break;
        case 'warning':
            $defaultIcon = 'warning';
            $defaultIconColor = '#F59E0B';
            break;
        case 'info':
        default:
            $defaultIcon = 'info';
            $defaultIconColor = '#3B82F6';
            break;
        case 'question':
            $defaultIcon = 'question';
            $defaultIconColor = '#8B5CF6';
            break;
    }

    $finalIcon = $icon ?? $defaultIcon;
    $finalIconColor = $iconColor ?? $defaultIconColor;

    // Configuración de clases para alertas estáticas
    switch ($type) {
        case 'info':
            $class = 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400 border border-blue-200';
            break;
        case 'danger':
        case 'error':
            $class = 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200';
            break;
        case 'success':
            $class = 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200';
            break;
        case 'warning':
            $class = 'text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 border border-yellow-200';
            break;
        case 'dark':
            $class = 'text-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-gray-300 border border-gray-200';
            break;
        default:
            $class = 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400 border border-blue-200';
            break;
    }
@endphp

@if($attributes->has('swal-trigger'))
<button type="button" 
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) }}
    onclick="showSweetAlert('{{ $type }}', '{{ $title }}', '{{ $text }}', '{{ $confirmText }}', '{{ $cancelText }}', {{ $showCancel ? 'true' : 'false' }}, '{{ $method }}', '{{ $formId }}', '{{ $url }}', '{{ $finalIcon }}', '{{ $finalIconColor }}', '{{ $footer }}', {{ $draggable ? 'true' : 'false' }})">
    {{ $slot }}
</button>
@else
<div {{ $attributes->merge(['class' => 'p-4 text-sm rounded-lg '. $class]) }} role="alert">
    <div class="flex items-center">
        @switch($type)
            @case('success')
                <i class="fas fa-check-circle mr-2"></i>
                @break
            @case('error')
            @case('danger')
                <i class="fas fa-exclamation-circle mr-2"></i>
                @break
            @case('warning')
                <i class="fas fa-exclamation-triangle mr-2"></i>
                @break
            @default
                <i class="fas fa-info-circle mr-2"></i>
        @endswitch
        <div>
            @if($title)
                <span class="font-medium">{{ $title }}</span>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function showSweetAlert(type, title, text, confirmText, cancelText, showCancel, method, formId, url, icon, iconColor, footer, draggable) {
    const swalConfig = {
        icon: icon,
        title: title || getDefaultTitle(type),
        text: text,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        showCancelButton: showCancel,
        confirmButtonColor: '#0C3E92',
        cancelButtonColor: '#6B7280',
        iconColor: iconColor,
        draggable: draggable,
        footer: footer || '',
        customClass: {
            popup: 'rounded-lg shadow-xl'
        }
    };

    Swal.fire(swalConfig).then((result) => {
        if (result.isConfirmed) {
            handleConfirmation(method, formId, url);
        }
    });
}

function getDefaultTitle(type) {
    const titles = {
        'success': '¡Éxito!',
        'error': 'Error',
        'warning': 'Advertencia',
        'info': 'Información',
        'question': 'Confirmación',
        'danger': 'Peligro'
    };
    return titles[type] || 'Alerta';
}

function handleConfirmation(method, formId, url) {
    if (formId) {
        const form = document.getElementById(formId);
        if (form) {
            if (method && method !== 'GET') {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = method;
                form.appendChild(methodInput);
            }
            form.submit();
        }
    } else if (url) {
        if (method === 'DELETE' || method === 'PUT' || method === 'PATCH') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = method;
            form.appendChild(methodInput);
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(tokenInput);
            
            document.body.appendChild(form);
            form.submit();
        } else {
            window.location.href = url;
        }
    }
}
</script>
@endpush