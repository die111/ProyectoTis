<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notificaciones SweetAlert2 globales
    @if(session('swal_custom'))
        Swal.fire({
            icon: '{{ session('swal_icon') }}',
            title: '{{ session('swal_title') }}',
            text: '{{ session('swal_text') }}',
            confirmButtonColor: '#0C3E92',
            iconColor: '#091c47',
        });
    @endif
    @if(session('swal_error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('swal_error') }}',
            confirmButtonColor: '#0C3E92',
            iconColor: '#091c47',
        });
    @endif

    // Confirmación para eliminar 
    document.querySelectorAll('.delete-phase-form, .swal-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Seguro que deseas eliminar?',
                icon: 'warning',
                iconColor: '#091c47',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
