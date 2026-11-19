<script setup>
// Muestra mensajes de sesión (éxito o error) enviados desde Laravel.
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const mensaje = computed(() => page.props.flash?.success || page.props.flash?.error);
const esExito = computed(() => Boolean(page.props.flash?.success));
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
    >
        <div
            v-if="mensaje"
            class="mb-4 rounded-lg px-4 py-3 text-sm"
            :class="
                esExito
                    ? 'border border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-900/40 dark:text-green-200'
                    : 'border border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-900/40 dark:text-red-200'
            "
            role="alert"
        >
            {{ mensaje }}
        </div>
    </Transition>
</template>