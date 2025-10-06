<footer class="bg-gray-800 text-white py-8 mt-auto relative z-10 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 efect">
        <div class="flex flex-col md:flex-row justify-between">
            <div class="mb-6 md:mb-0 text-center md:text-left ">
                <h4 class="font-bold text-lg mb-4">UNIVERSIDAD MAYOR DE SAN SIMÓN</h4>
                <p class="mt-2">Dirección: Av. Oquendo y Jordan, Cochabamba - Bolivia<br>Teléfono: (591)</p>
            </div>
            <div class="text-center md:text-right">
                <p>Copyright © {{ date('Y') }} FullCoders - Todos los derechos reservados</p>
                <p class="mt-2">Proyecto desarrollado en colaboración con la<br>Universidad Mayor de San Simón</p>
                <p class="mt-2">Web diseñada y gestionada por FullCoders</p>
            </div>
        </div>
    </div>
</footer>
<style>
    .bg-gray-800 {
        background-color: #091C47;
    }


    /*7) EFECTOS DE APARICIÓN */
/*Efecto de aparición de escala*/
.efect{
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.efect.mostrar {
    opacity: 1;
    transform: scale(1);
}
/*Efecto de aparición lateral de una sola vez*/
.efect2 {
    opacity: 0;
    transform: translateX(20%);
    transition: all 0.5s ease-in-out;
}
  
.efect2.in-view {
    opacity: 1;
    transform: translateX(0);
}
/*Efecto de aparición central de una sola vez*/
.efect3 {
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
  
.efect3.in-view2 {
    opacity: 1;
    transform: scale(1);
}
</style> 
<script>
    /*funcion para manejar lps 3 efectos:
efect: aparicion de escala
efect2: aparición única lateral
efect3: aparición única
*/
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const classMap = {
                    'efect': 'mostrar',
                    'efect2': 'in-view',
                    'efect3': 'in-view2'
                };
                for (const [elementClass, effectClass] of Object.entries(classMap)) {
                    if (entry.target.classList.contains(elementClass)) {
                        entry.target.classList.add(effectClass);
                        // Si es efecto único, dejar de observar
                        if (elementClass !== 'efect') {
                            observer.unobserve(entry.target);
                        }
                        break;
                    }
                }
            } else {
                if (entry.target.classList.contains('efect')) {
                    entry.target.classList.remove('mostrar');
                }
            }
        });
    }, observerOptions);
    const elements = document.querySelectorAll('.efect, .efect2, .efect3');
    elements.forEach(element => observer.observe(element));
});
</script>