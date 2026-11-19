<script setup>
/**
 * Contenedor visual para modales: fondo semitransparente y panel centrado.
 * Emite "cerrar" al hacer clic fuera del panel (si no está deshabilitado).
 */
defineProps({
    abierto: { type: Boolean, default: false },
    tituloId: { type: String, default: 'modal-titulo' },
    deshabilitarCerrar: { type: Boolean, default: false },
});

const emit = defineEmits(['cerrar']);

function cerrarFondo() {
    emit('cerrar');
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="abierto"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-[1px]"
            role="dialog"
            aria-modal="true"
            :aria-labelledby="tituloId"
            @click.self="!deshabilitarCerrar && cerrarFondo()"
        >
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="abierto"
                    class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-600 dark:bg-gray-800"
                    @click.stop
                >
                    <slot />
                </div>
            </Transition>
        </div>
    </Transition>
</template>