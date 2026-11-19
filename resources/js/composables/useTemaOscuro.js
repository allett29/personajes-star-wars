import { ref } from 'vue';

const CLAVE_STORAGE = 'personajes-tema-oscuro';

/** Estado compartido en toda la app (un solo toggle de tema). */
const temaOscuroActivo = ref(false);

/** Aplica o quita la clase `dark` en <html> para Tailwind. */
function aplicarEnHtml(esOscuro) {
    document.documentElement.classList.toggle('dark', esOscuro);
}

/**
 * Dark mode con persistencia en localStorage.
 * Se inicializa una vez desde AppLayout al montar la página.
 */
export function useTemaOscuro() {
    function inicializar() {
        const guardado = localStorage.getItem(CLAVE_STORAGE);
        temaOscuroActivo.value = guardado === '1';
        aplicarEnHtml(temaOscuroActivo.value);
    }

    function alternar() {
        temaOscuroActivo.value = !temaOscuroActivo.value;
        localStorage.setItem(CLAVE_STORAGE, temaOscuroActivo.value ? '1' : '0');
        aplicarEnHtml(temaOscuroActivo.value);
    }

    return {
        activo: temaOscuroActivo,
        inicializar,
        alternar,
    };
}