<script setup>
// Mensajes flash del servidor (éxito o error), con animación de entrada.
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const mensaje = computed(() => page.props.flash?.success || page.props.flash?.error);
const esExito = computed(() => Boolean(page.props.flash?.success));
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="mensaje"
            class="mb-4 rounded-lg border px-4 py-3 text-sm shadow-sm"
            :class="
                esExito
                    ? 'border-green-200 bg-green-50 text-green-900 dark:border-green-800 dark:bg-green-950/50 dark:text-green-100'
                    : 'border-red-200 bg-red-50 text-red-900 dark:border-red-900 dark:bg-red-950/40 dark:text-red-100'
            "
            role="alert"
        >
            {{ mensaje }}
        </div>
    </Transition>
</template>