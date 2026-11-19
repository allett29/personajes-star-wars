import { router } from '@inertiajs/vue3';

/**
 * Arma la query string del listado sin parámetros vacíos.
 */
export function construirParametros(filtros) {
    const params = { ...filtros };

    Object.keys(params).forEach((clave) => {
        if (params[clave] === '' || params[clave] === null || params[clave] === undefined) {
            delete params[clave];
        }
    });

    return params;
}

/** Navega al índice conservando filtros en la URL (patrón del skill Inertia). */
export function visitarListado(filtros) {
    router.get('/personajes', construirParametros(filtros), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}