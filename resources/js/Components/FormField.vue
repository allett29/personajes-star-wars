<script setup>
/**
 * Envoltorio de un campo de formulario: etiqueta, control (slot) y mensaje de error.
 * Evita repetir la misma estructura en cada input.
 */
defineProps({
    etiqueta: { type: String, required: true },
    para: { type: String, required: true },
    error: { type: String, default: '' },
    requerido: { type: Boolean, default: false },
});
</script>

<template>
    <div>
        <label :for="para" class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
            {{ etiqueta }}
            <span v-if="requerido" class="text-red-500" aria-hidden="true">*</span>
        </label>
        <slot />
        <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>
