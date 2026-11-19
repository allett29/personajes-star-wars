<script setup>
/**
 * Layout común: cabecera, toggle de tema oscuro y área de contenido.
 */
import { useTemaOscuro } from '@/composables/useTemaOscuro';
import { onMounted } from 'vue';

const { activo: temaOscuro, inicializar, alternar } = useTemaOscuro();

onMounted(() => {
    inicializar();
});
</script>

<template>
    <div class="min-h-screen bg-[var(--surface-page)] text-[var(--text-primary)]">
        <header
            class="border-b border-gray-200 bg-white/80 backdrop-blur dark:border-gray-700 dark:bg-gray-900/80"
        >
            <div
                class="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 text-sm text-gray-600 dark:text-gray-300"
            >
                <span class="font-medium text-gray-800 dark:text-gray-100">Personajes Star Wars</span>
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-3 py-1.5 transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800"
                    :aria-pressed="temaOscuro"
                    @click="alternar"
                >
                    {{ temaOscuro ? 'Modo claro' : 'Modo oscuro' }}
                </button>
            </div>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>