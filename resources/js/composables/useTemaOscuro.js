import { ref } from 'vue';

const CLAVE_STORAGE = 'tema-oscuro';

/**
 * Persiste el modo oscuro en localStorage y la clase `dark` en <html>.
 */
export function useTemaOscuro() {
    const activo = ref(false);

    function aplicarEnHtml(esOscuro) {
        document.documentElement.classList.toggle('dark', esOscuro);
    }

    function inicializar() {
        const guardado = localStorage.getItem(CLAVE_STORAGE);
        const usarOscuro =
            guardado === '1' ||
            (guardado === null && window.matchMedia('(prefers-color-scheme: dark)').matches);

        activo.value = usarOscuro;
        aplicarEnHtml(usarOscuro);
    }

    function alternar() {
        activo.value = !activo.value;
        localStorage.setItem(CLAVE_STORAGE, activo.value ? '1' : '0');
        aplicarEnHtml(activo.value);
    }

    return { activo, inicializar, alternar };
}