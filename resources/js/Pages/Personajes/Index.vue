<script setup>
/**
 * Listado de personajes: búsqueda en tiempo real, filtro por género,
 * orden por columna, paginación y eliminación con confirmación.
 * Los filtros viven en la URL y el servidor hace la consulta.
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import ImportModal from '@/Components/ImportModal.vue';
import OrigenBadge from '@/Components/OrigenBadge.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Paginacion from '@/Components/Paginacion.vue';
import { visitarListado } from '@/composables/useFiltrosListado.js';
import { btnPrimario, btnSecundario, btnTexto, btnTextoPeligro, inputBase, tarjeta } from '@/lib/clasesUi.js';
import { GENEROS, textoGenero } from '@/lib/opciones.js';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    personajes: { type: Object, required: true },
    filtros: { type: Object, required: true },
});

const columnasOrdenables = [
    { clave: 'nombre', titulo: 'Nombre' },
    { clave: 'altura', titulo: 'Altura' },
    { clave: 'masa', titulo: 'Masa' },
    { clave: 'genero', titulo: 'Género' },
];

const modalImportarAbierto = ref(false);

/* ---------- Filtros ---------- */

const busqueda = ref(props.filtros.buscar ?? '');
const genero = ref(props.filtros.genero ?? '');

const hayFiltros = computed(() => busqueda.value.trim() !== '' || genero.value !== '');

/** Recarga el listado con los filtros actuales; `cambios` reemplaza valores puntuales. */
function recargar(cambios = {}) {
    visitarListado({
        buscar: busqueda.value.trim(),
        genero: genero.value,
        orden: props.filtros.orden,
        direccion: props.filtros.direccion,
        ...cambios,
    });
}

// Búsqueda en tiempo real: espera 350 ms después de la última tecla antes de consultar.
let temporizador = null;
watch(busqueda, () => {
    clearTimeout(temporizador);
    temporizador = setTimeout(() => {
        if (busqueda.value.trim() !== (props.filtros.buscar ?? '')) {
            recargar();
        }
    }, 350);
});

watch(genero, () => recargar());

function limpiarFiltros() {
    busqueda.value = '';
    genero.value = '';
}

/* ---------- Orden ---------- */

/** Clic en una columna: ordena ascendente; un segundo clic invierte el orden. */
function ordenarPor(columna) {
    const esLaMisma = props.filtros.orden === columna;
    const direccion = esLaMisma && props.filtros.direccion === 'asc' ? 'desc' : 'asc';
    recargar({ orden: columna, direccion });
}

function flechaOrden(columna) {
    if (props.filtros.orden !== columna) {
        return '';
    }
    return props.filtros.direccion === 'asc' ? '↑' : '↓';
}

/* ---------- Eliminar ---------- */

const personajeAEliminar = ref(null);
const eliminando = ref(false);

function cancelarEliminar() {
    if (!eliminando.value) {
        personajeAEliminar.value = null;
    }
}

function confirmarEliminar() {
    eliminando.value = true;
    router.delete(`/personajes/${personajeAEliminar.value.id}`, {
        preserveScroll: true,
        onFinish: () => {
            eliminando.value = false;
            personajeAEliminar.value = null;
        },
    });
}

/** Muestra el número con su unidad, o una raya si no hay dato. */
function conUnidad(valor, unidad) {
    return valor === null ? '—' : `${valor} ${unidad}`;
}
</script>

<template>
    <Head title="Personajes" />

    <PageHeader titulo="Personajes" :descripcion="`${personajes.total} en el archivo, creados a mano o importados desde SWAPI.`">
        <template #acciones>
            <button type="button" :class="btnSecundario" @click="modalImportarAbierto = true">Importar desde SWAPI</button>
            <Link href="/personajes/crear" :class="btnPrimario">Crear nuevo</Link>
        </template>
    </PageHeader>

    <!-- Filtros -->
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="buscar" class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">Buscar por nombre</label>
            <input id="buscar" v-model="busqueda" type="search" placeholder="Por ejemplo, Leia" autocomplete="off" :class="inputBase" />
        </div>
        <div class="sm:w-48">
            <label for="filtro-genero" class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">Género</label>
            <select id="filtro-genero" v-model="genero" :class="inputBase">
                <option value="">Todos</option>
                <option v-for="opcion in GENEROS" :key="opcion.valor" :value="opcion.valor">{{ opcion.texto }}</option>
            </select>
        </div>
        <button v-if="hayFiltros" type="button" :class="btnSecundario" @click="limpiarFiltros">Limpiar filtros</button>
    </div>

    <!-- Tabla -->
    <div :class="[tarjeta, 'overflow-hidden']">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-espacio-800 dark:bg-espacio-950/40 dark:text-slate-300">
                    <tr>
                        <th v-for="columna in columnasOrdenables" :key="columna.clave" scope="col" class="px-4 py-3 font-medium">
                            <button
                                type="button"
                                class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-white"
                                @click="ordenarPor(columna.clave)"
                            >
                                {{ columna.titulo }}
                                <span class="text-xs" aria-hidden="true">{{ flechaOrden(columna.clave) }}</span>
                            </button>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium">Origen</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="personaje in personajes.data"
                        :key="personaje.id"
                        class="border-b border-slate-100 last:border-0 dark:border-espacio-800"
                    >
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ personaje.nombre }}</td>
                        <td class="whitespace-nowrap px-4 py-3 tabular-nums">{{ conUnidad(personaje.altura, 'cm') }}</td>
                        <td class="whitespace-nowrap px-4 py-3 tabular-nums">{{ conUnidad(personaje.masa, 'kg') }}</td>
                        <td class="px-4 py-3">{{ textoGenero(personaje.genero) }}</td>
                        <td class="px-4 py-3"><OrigenBadge :swapi="personaje.origen_swapi" /></td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <Link :href="`/personajes/${personaje.id}/editar`" :class="btnTexto">Editar</Link>
                            <button type="button" :class="btnTextoPeligro" @click="personajeAEliminar = personaje">Eliminar</button>
                        </td>
                    </tr>

                    <!-- Estado vacío: indica qué hacer a continuación -->
                    <tr v-if="personajes.data.length === 0">
                        <td colspan="6" class="px-4 py-12 text-center">
                            <p class="font-medium text-slate-900 dark:text-white">
                                {{ hayFiltros ? 'Ningún personaje coincide con la búsqueda' : 'Todavía no hay personajes' }}
                            </p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ hayFiltros ? 'Prueba con otro nombre o limpia los filtros.' : 'Crea uno nuevo o importa uno desde SWAPI.' }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <Paginacion :paginador="personajes" />

    <ImportModal :abierto="modalImportarAbierto" @cerrar="modalImportarAbierto = false" />

    <ConfirmDialog
        :abierto="personajeAEliminar !== null"
        titulo="Eliminar personaje"
        :mensaje="`Se eliminará a ${personajeAEliminar?.nombre} de forma permanente.`"
        texto-confirmar="Eliminar"
        :cargando="eliminando"
        @confirmar="confirmarEliminar"
        @cancelar="cancelarEliminar"
    />
</template>
