<script setup>
// Muestra mensajes de sesión (éxito o error) enviados desde Laravel.
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const mensaje = computed(() => page.props.flash?.success || page.props.flash?.error);
const esExito = computed(() => Boolean(page.props.flash?.success));
</script>

<template>
    <div
        v-if="mensaje"
        class="mb-4 rounded px-4 py-3 text-sm"
        :class="esExito ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        role="alert"
    >
        {{ mensaje }}
    </div>
</template>