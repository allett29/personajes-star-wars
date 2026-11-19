<script setup>
// Lista de personajes con paginación, acciones de editar y eliminar.
import FlashMessage from '@/Components/FlashMessage.vue';
import ImportModal from '@/Components/ImportModal.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    personajes: {
        type: Object,
        required: true,
    },
    filtros: {
        type: Object,
        default: () => ({ genero: '' }),
    },
});

/** Etiqueta legible para el origen del dato. */
function etiquetaOrigen(origenSwapi) {
    return origenSwapi ? 'SWAPI' : 'Manual';
}

const modalImportarAbierto = ref(false);

/** Aplica el filtro por género en la URL sin recargar toda la app. */
function cambiarFiltroGenero(evento) {
    const genero = evento.target.value;
    const parametros = genero ? { genero } : {};

    router.get('/personajes', parametros, {
        preserveState: true,
        replace: true,
    });
}

/** Pide confirmación antes de eliminar un registro. */
function eliminar(id) {
    if (window.confirm('¿Eliminar este personaje?')) {
        router.delete(`/personajes/${id}`);
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-5xl px-4">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Personajes Star Wars</h1>
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="rounded bg-amber-600 px-4 py-2 text-sm text-white hover:bg-amber-700"
                        @click="modalImportarAbierto = true"
                    >
                        Importar desde SWAPI
                    </button>
                    <Link
                        href="/personajes/crear"
                        class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700"
                    >
                        Crear nuevo
                    </Link>
                </div>
            </div>

            <ImportModal :abierto="modalImportarAbierto" @cerrar="modalImportarAbierto = false" />

            <FlashMessage />

            <!-- Bonus: filtro simple por género (query string ?genero=) -->
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <label class="text-sm text-gray-700" for="filtro-genero">Filtrar por género:</label>
                <select
                    id="filtro-genero"
                    class="rounded border-gray-300 text-sm shadow-sm"
                    :value="props.filtros.genero"
                    @change="cambiarFiltroGenero"
                >
                    <option value="">Todos</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="n/a">n/a</option>
                </select>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Altura</th>
                            <th class="px-4 py-3">Masa</th>
                            <th class="px-4 py-3">Género</th>
                            <th class="px-4 py-3">Origen</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="personaje in personajes.data"
                            :key="personaje.id"
                            class="border-t border-gray-100 hover:bg-gray-50"
                        >
                            <td class="px-4 py-3 font-medium">{{ personaje.nombre }}</td>
                            <td class="px-4 py-3">{{ personaje.altura ?? '—' }}</td>
                            <td class="px-4 py-3">{{ personaje.masa ?? '—' }}</td>
                            <td class="px-4 py-3">{{ personaje.genero }}</td>
                            <td class="px-4 py-3">{{ etiquetaOrigen(personaje.origen_swapi) }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/personajes/${personaje.id}/editar`"
                                    class="text-indigo-600 hover:underline"
                                >
                                    Editar
                                </Link>
                                <button
                                    type="button"
                                    class="ml-3 text-red-600 hover:underline"
                                    @click="eliminar(personaje.id)"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr v-if="personajes.data.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No hay personajes. Crea uno nuevo para comenzar.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación de Laravel (10 por página) -->
            <div v-if="personajes.links.length > 3" class="mt-4 flex flex-wrap justify-center gap-1">
                <template v-for="(link, i) in personajes.links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="rounded border px-3 py-1 text-sm"
                        :class="
                            link.active
                                ? 'border-indigo-600 bg-indigo-600 text-white'
                                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100'
                        "
                        v-html="link.label"
                    />
                    <span v-else class="rounded border border-gray-200 px-3 py-1 text-sm text-gray-400" v-html="link.label" />
                </template>
            </div>
        </div>
    </div>
</template>