<script setup>
/**
 * Muestra las notificaciones (toasts) en la esquina superior derecha.
 * Convierte automáticamente los mensajes flash de Laravel (éxito / error) en toasts.
 */
import { useToast } from '@/composables/useToast.js';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const page = usePage();
const { toasts, mostrar, cerrar } = useToast();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            mostrar(flash.success, 'exito');
        }
        if (flash?.error) {
            mostrar(flash.error, 'error');
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="pointer-events-none fixed right-4 top-4 z-[60] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-2" aria-live="polite">
        <TransitionGroup
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 opacity-0"
            leave-active-class="transition duration-150 ease-in"
            leave-to-class="opacity-0"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                role="status"
                class="pointer-events-auto flex items-start gap-3 rounded-lg border bg-white p-4 shadow-lg dark:bg-espacio-900"
                :class="toast.tipo === 'exito' ? 'border-emerald-200 dark:border-emerald-500/30' : 'border-red-200 dark:border-red-500/30'"
            >
                <span
                    class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full text-xs font-bold text-white"
                    :class="toast.tipo === 'exito' ? 'bg-emerald-500' : 'bg-red-500'"
                    aria-hidden="true"
                >
                    {{ toast.tipo === 'exito' ? '✓' : '!' }}
                </span>
                <p class="flex-1 text-sm text-slate-800 dark:text-slate-100">{{ toast.mensaje }}</p>
                <button
                    type="button"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                    aria-label="Cerrar notificación"
                    @click="cerrar(toast.id)"
                >
                    ✕
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>
