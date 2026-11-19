<script setup>
/** Etiqueta que indica de dónde viene el personaje: importado de SWAPI o creado a mano. */
defineProps({
    swapi: { type: Boolean, default: false },
});
</script>

<template>
    <span
        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
        :class="
            swapi
                ? 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300'
                : 'bg-slate-100 text-slate-700 dark:bg-espacio-800 dark:text-slate-300'
        "
    >
        {{ swapi ? 'SWAPI' : 'Manual' }}
    </span>
</template>
