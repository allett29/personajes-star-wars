<script setup>
// Listado con búsqueda, filtros, ordenamiento, paginación y confirmación al eliminar.
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import ImportModal from '@/Components/ImportModal.vue';
import {
    btnAcento,
    btnPrimario,
    enlaceAccion,
    labelCampo,
    pageContainer,
    pageWrapper,
    selectCampo,
    tablaFila,
    tablaHead,
    tablaWrap,
    tituloPagina,
    inputCampo,
} from '@/lib/clasesUi';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    personajes: { type: Object, required: true },
    filtros: {
        type: Object,
        default: () => ({
            genero: '',
            buscar: '',
            ordenar: 'nombre',
            direccion: 'asc',
        }),
    },
});

const modalImportarAbierto = ref(false);
const textoBusqueda = ref(props.filtros.buscar || '');
const confirmacion = ref({ abierto: false, id: null, nombre: '' });

/** Arma los query params del listado y conserva filtros activos. */
function irAListado(cambios = {}) {
    const params = {
        genero: props.filtros.genero || undefined,
        buscar: props.filtros.buscar || undefined,
        ordenar: props.filtros.ordenar || 'nombre',
        direccion: props.filtros.direccion || 'asc',
        ...cambios,
    };

    Object.keys(params).forEach((clave) => {
        if (params[clave] === '' || params[clave] === undefined) {
            delete params[clave];
        }
    });

    router.get('/personajes', params, { preserveState: true, replace: true });
}

function etiquetaOrigen(origenSwapi) {
    return origenSwapi ? 'SWAPI' : 'Manual';
}

function cambiarFiltroGenero(evento) {
    irAListado({ genero: evento.target.value || undefined });
}

function buscarPorNombre() {
    irAListado({ buscar: textoBusqueda.value.trim() || undefined });
}

/** Alterna asc/desc al hacer clic en un encabezado de columna. */
function ordenarPor(columna) {
    let direccion = 'asc';
    if (props.filtros.ordenar === columna && props.filtros.direccion === 'asc') {
        direccion = 'desc';
    }
    irAListado({ ordenar: columna, direccion });
}

function iconoOrden(columna) {
    if (props.filtros.ordenar !== columna) {
        return '';
    }
    return props.filtros.direccion === 'asc' ? ' ↑' : ' ↓';
}

function solicitarEliminar(personaje) {
    confirmacion.value = { abierto: true, id: personaje.id, nombre: personaje.nombre };
}

function confirmarEliminar() {
    router.delete(`/personajes/${confirmacion.value.id}`, {
        onFinish: () => {
            confirmacion.value = { abierto: false, id: null, nombre: '' };
        },
    });
}
</script>

<template>
    <AppLayout>
        <div :class="pageWrapper">
            <div :class="pageContainer">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h1 :class="tituloPagina">Personajes Star Wars</h1>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" :class="btnAcento" @click="modalImportarAbierto = true">
                            Importar desde SWAPI
                        </button>
                        <Link href="/personajes/crear" :class="btnPrimario">Crear nuevo</Link>
                    </div>
                </div>

                <ImportModal :abierto="modalImportarAbierto" @cerrar="modalImportarAbierto = false" />

                <ConfirmDialog
                    :abierto="confirmacion.abierto"
                    titulo="Eliminar personaje"
                    :mensaje="`¿Eliminar a ${confirmacion.nombre}? Esta acción no se puede deshacer.`"
                    texto-confirmar="Eliminar"
                    @confirmar="confirmarEliminar"
                    @cancelar="confirmacion.abierto = false"
                />

                <FlashMessage />

                <div
                    class="mb-4 flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:flex-wrap sm:items-end"
                >
                    <div class="flex-1 min-w-[200px]">
                        <label :class="labelCampo" for="buscar">Buscar por nombre</label>
                        <input
                            id="buscar"
                            v-model="textoBusqueda"
                            type="search"
                            placeholder="Luke, Leia…"
                            :class="inputCampo"
                            @keyup.enter="buscarPorNombre"
                        />
                    </div>
                    <div>
                        <label :class="labelCampo" for="filtro-genero">Género</label>
                        <select
                            id="filtro-genero"
                            :class="selectCampo"
                            :value="props.filtros.genero"
                            @change="cambiarFiltroGenero"
                        >
                            <option value="">Todos</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="n/a">n/a</option>
                        </select>
                    </div>
                    <button type="button" :class="btnPrimario" @click="buscarPorNombre">Buscar</button>
                </div>

                <div :class="tablaWrap">
                    <table class="min-w-full text-left text-sm">
                        <thead :class="tablaHead">
                            <tr>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('nombre')">
                                        Nombre{{ iconoOrden('nombre') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('altura')">
                                        Altura{{ iconoOrden('altura') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('masa')">
                                        Masa{{ iconoOrden('masa') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('genero')">
                                        Género{{ iconoOrden('genero') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">Origen</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-200">
                            <tr v-for="personaje in personajes.data" :key="personaje.id" :class="tablaFila">
                                <td class="px-4 py-3 font-medium">{{ personaje.nombre }}</td>
                                <td class="px-4 py-3">{{ personaje.altura ?? '—' }}</td>
                                <td class="px-4 py-3">{{ personaje.masa ?? '—' }}</td>
                                <td class="px-4 py-3">{{ personaje.genero }}</td>
                                <td class="px-4 py-3">{{ etiquetaOrigen(personaje.origen_swapi) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <Link :href="`/personajes/${personaje.id}/editar`" :class="enlaceAccion">Editar</Link>
                                    <button
                                        type="button"
                                        class="ml-3 font-medium text-red-600 hover:underline dark:text-red-400"
                                        @click="solicitarEliminar(personaje)"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="personajes.data.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No hay personajes con estos filtros.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="personajes.links.length > 3" class="mt-4 flex flex-wrap justify-center gap-1">
                    <template v-for="(link, i) in personajes.links" :key="i">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="rounded-lg border px-3 py-1 text-sm transition"
                            :class="
                                link.active
                                    ? 'border-indigo-600 bg-indigo-600 text-white'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700'
                            "
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="rounded-lg border border-gray-200 px-3 py-1 text-sm text-gray-400 dark:border-gray-700"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>