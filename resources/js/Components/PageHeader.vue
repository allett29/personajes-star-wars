<script setup>
/**
 * Cabecera de cada página: título, descripción opcional y botones de acción (slot "acciones").
 */
defineProps({
    titulo: { type: String, required: true },
    descripcion: { type: String, default: '' },
});
</script>

<template>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-titulo text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ titulo }}</h1>
            <p v-if="descripcion" class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ descripcion }}</p>
        </div>
        <div v-if="$slots.acciones" class="flex flex-wrap gap-2">
            <slot name="acciones" />
        </div>
    </div>
</template>
