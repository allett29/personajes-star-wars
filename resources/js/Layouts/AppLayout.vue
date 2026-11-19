<script setup>
/**
 * Layout común de todas las páginas: barra superior, cambio de tema y notificaciones.
 * Es persistente: no se vuelve a montar al navegar, así los toasts no se pierden.
 */
import ToastContainer from '@/Components/ToastContainer.vue';
import { useTemaOscuro } from '@/composables/useTemaOscuro.js';
import { btnSecundario } from '@/lib/clasesUi.js';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const { activo: temaOscuro, inicializar, alternar } = useTemaOscuro();

onMounted(inicializar);
</script>

<template>
    <div class="min-h-screen bg-hoth font-sans text-slate-700 dark:bg-espacio-950 dark:text-slate-300">
        <header class="border-b border-slate-200 bg-white dark:border-espacio-800 dark:bg-espacio-900">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
                <Link href="/personajes" class="font-titulo text-lg font-bold text-slate-900 dark:text-white">
                    Archivo Star Wars
                </Link>
                <button type="button" :class="btnSecundario" :aria-pressed="temaOscuro" @click="alternar">
                    <span aria-hidden="true">{{ temaOscuro ? '☀' : '☾' }}</span>
                    {{ temaOscuro ? 'Modo claro' : 'Modo oscuro' }}
                </button>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8">
            <slot />
        </main>

        <ToastContainer />
    </div>
</template>
