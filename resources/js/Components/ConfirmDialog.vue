<script setup>
/**
 * Reemplaza window.confirm: diálogo accesible y coherente con el diseño.
 */
import ModalBase from '@/Components/ModalBase.vue';
import { btnPeligro, btnSecundario } from '@/lib/clasesUi';

defineProps({
    abierto: { type: Boolean, default: false },
    titulo: { type: String, default: 'Confirmar acción' },
    mensaje: { type: String, required: true },
    textoConfirmar: { type: String, default: 'Confirmar' },
    cargando: { type: Boolean, default: false },
});

const emit = defineEmits(['confirmar', 'cancelar']);
</script>

<template>
    <ModalBase
        :abierto="abierto"
        titulo-id="confirmar-titulo"
        :deshabilitar-cerrar="cargando"
        @cerrar="emit('cancelar')"
    >
        <h2 id="confirmar-titulo" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ titulo }}
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ mensaje }}</p>
        <div class="mt-6 flex justify-end gap-2">
            <button type="button" :class="btnSecundario" :disabled="cargando" @click="emit('cancelar')">
                Cancelar
            </button>
            <button type="button" :class="btnPeligro" :disabled="cargando" @click="emit('confirmar')">
                {{ cargando ? 'Procesando…' : textoConfirmar }}
            </button>
        </div>
    </ModalBase>
</template>