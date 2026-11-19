import { reactive } from 'vue';

// Lista compartida: cualquier componente que use useToast() ve las mismas notificaciones.
const toasts = reactive([]);
let siguienteId = 1;

/**
 * Notificaciones temporales (toasts) de éxito o error.
 * Uso: const { exito, error } = useToast(); exito('Personaje guardado');
 */
export function useToast() {
    function cerrar(id) {
        const indice = toasts.findIndex((toast) => toast.id === id);
        if (indice !== -1) {
            toasts.splice(indice, 1);
        }
    }

    function mostrar(mensaje, tipo = 'exito', duracionMs = 4000) {
        const id = siguienteId++;
        toasts.push({ id, mensaje, tipo });
        setTimeout(() => cerrar(id), duracionMs);
    }

    return {
        toasts,
        mostrar,
        cerrar,
        exito: (mensaje) => mostrar(mensaje, 'exito'),
        error: (mensaje) => mostrar(mensaje, 'error'),
    };
}
