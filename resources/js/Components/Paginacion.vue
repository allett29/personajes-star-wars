<script setup>
/**
 * Paginación de Laravel en español.
 * Recibe el objeto paginado completo que envía el controlador (links, from, to, total).
 */
import { Link } from '@inertiajs/vue3';

defineProps({
    paginador: { type: Object, required: true },
});

/** Laravel envía "Previous" y "Next" en inglés; aquí se traducen. */
function etiqueta(texto) {
    if (texto.includes('Previous')) {
        return 'Anterior';
    }
    if (texto.includes('Next')) {
        return 'Siguiente';
    }
    return texto;
}
</script>

<template>
    <div v-if="paginador.total > 0" class="mt-4 flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Mostrando {{ paginador.from }}–{{ paginador.to }} de {{ paginador.total }}
        </p>

        <nav v-if="paginador.last_page > 1" class="flex flex-wrap gap-1" aria-label="Paginación">
            <template v-for="(enlace, indice) in paginador.links" :key="indice">
                <Link
                    v-if="enlace.url"
                    :href="enlace.url"
                    preserve-scroll
                    class="rounded-lg px-3 py-1.5 text-sm transition"
                    :class="
                        enlace.active
                            ? 'bg-blue-600 text-white dark:bg-blue-500'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-espacio-800'
                    "
                    :aria-current="enlace.active ? 'page' : undefined"
                >
                    {{ etiqueta(enlace.label) }}
                </Link>
                <span v-else class="px-3 py-1.5 text-sm text-slate-400 dark:text-slate-600">
                    {{ etiqueta(enlace.label) }}
                </span>
            </template>
        </nav>
    </div>
</template>
