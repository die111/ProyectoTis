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