<script setup>
/**
 * Base de todos los modales: fondo oscurecido y panel centrado.
 * Se cierra con la tecla Esc o con clic fuera del panel.
 * `bloquearCierre` impide cerrarlo mientras algo se está procesando.
 */
import { onBeforeUnmount, onMounted } from 'vue';

const props = defineProps({
    abierto: { type: Boolean, default: false },
    tituloId: { type: String, required: true },
    bloquearCierre: { type: Boolean, default: false },
});

const emit = defineEmits(['cerrar']);

function intentarCerrar() {
    if (!props.bloquearCierre) {
        emit('cerrar');
    }
}

function alPresionarTecla(evento) {
    if (evento.key === 'Escape' && props.abierto) {
        intentarCerrar();
    }
}

onMounted(() => window.addEventListener('keydown', alPresionarTecla));
onBeforeUnmount(() => window.removeEventListener('keydown', alPresionarTecla));
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            leave-active-class="transition duration-100 ease-in"
            leave-to-class="opacity-0"
        >
            <div
                v-if="abierto"
                class="fixed inset-0 z-50 flex items-end justify-center bg-espacio-950/60 p-4 sm:items-center"
                @click.self="intentarCerrar"
            >
                <div
                    role="dialog"
                    aria-modal="true"
                    :aria-labelledby="tituloId"
                    class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-xl dark:border-espacio-800 dark:bg-espacio-900"
                >
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
