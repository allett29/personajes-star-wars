<script setup>
/**
 * Cabecera de página: título, descripción opcional y zona de acciones (botones).
 */
defineProps({
    titulo: { type: String, required: true },
    descripcion: { type: String, default: '' },
});
</script>

<template>
    <header class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-50">{{ titulo }}</h1>
            <p v-if="descripcion" class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ descripcion }}</p>
        </div>
        <div v-if="$slots.acciones" class="flex flex-wrap gap-2">
            <slot name="acciones" />
        </div>
    </header>
</template>