<script setup>
/**
 * Listado principal: filtros en URL, tabla ordenable, confirmación al eliminar.
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import CardPanel from '@/Components/CardPanel.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import ImportModal from '@/Components/ImportModal.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { visitarListado } from '@/composables/useFiltrosListado';
import { btnAdvertencia, btnEnlace, btnEnlacePeligro, btnPrimario, filaTabla, selectBase, tablaCabecera } from '@/lib/clasesUi';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    personajes: { type: Object, required: true },
    filtros: {
        type: Object,
        default: () => ({
            genero: '',
            buscar: '',
            orden: 'nombre',
            direccion: 'asc',
        }),
    },
});

const modalImportarAbierto = ref(false);
const confirmarEliminar = ref(false);
const idEliminar = ref(null);
const nombreEliminar = ref('');
const eliminando = ref(false);

const textoBusqueda = ref(props.filtros.buscar ?? '');

/** Parámetros actuales para conservar al cambiar un solo filtro. */
const filtrosBase = computed(() => ({
    genero: props.filtros.genero ?? '',
    buscar: props.filtros.buscar ?? '',
    orden: props.filtros.orden ?? 'nombre',
    direccion: props.filtros.direccion ?? 'asc',
}));

function etiquetaOrigen(origenSwapi) {
    return origenSwapi ? 'SWAPI' : 'Manual';
}

function aplicarBusqueda() {
    visitarListado({ ...filtrosBase.value, buscar: textoBusqueda.value.trim() });
}

function cambiarGenero(evento) {
    visitarListado({ ...filtrosBase.value, genero: evento.target.value });
}

/** Alterna asc/desc al hacer clic en el encabezado de columna. */
function ordenarPor(columna) {
    const direccion =
        props.filtros.orden === columna && props.filtros.direccion === 'asc' ? 'desc' : 'asc';

    visitarListado({
        ...filtrosBase.value,
        orden: columna,
        direccion,
    });
}

function indicadorOrden(columna) {
    if (props.filtros.orden !== columna) {
        return '';
    }

    return props.filtros.direccion === 'asc' ? ' ↑' : ' ↓';
}

function pedirEliminar(personaje) {
    idEliminar.value = personaje.id;
    nombreEliminar.value = personaje.nombre;
    confirmarEliminar.value = true;
}

function cancelarEliminar() {
    if (eliminando.value) {
        return;
    }
    confirmarEliminar.value = false;
    idEliminar.value = null;
    nombreEliminar.value = '';
}

function confirmarEliminacion() {
    eliminando.value = true;
    router.delete(`/personajes/${idEliminar.value}`, {
        preserveScroll: true,
        onFinish: () => {
            eliminando.value = false;
            cancelarEliminar();
        },
    });
}
</script>

<template>
    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4">
            <PageHeader titulo="Personajes Star Wars" descripcion="Gestión manual e importación desde SWAPI.">
                <template #acciones>
                    <button type="button" :class="btnAdvertencia" @click="modalImportarAbierto = true">
                        Importar SWAPI
                    </button>
                    <Link href="/personajes/crear" :class="btnPrimario">Crear nuevo</Link>
                </template>
            </PageHeader>

            <ImportModal :abierto="modalImportarAbierto" @cerrar="modalImportarAbierto = false" />
            <FlashMessage />

            <ConfirmDialog
                :abierto="confirmarEliminar"
                titulo="Eliminar personaje"
                :mensaje="`¿Eliminar a «${nombreEliminar}»? Esta acción no se puede deshacer.`"
                texto-confirmar="Eliminar"
                :cargando="eliminando"
                @confirmar="confirmarEliminacion"
                @cancelar="cancelarEliminar"
            />

            <CardPanel class="mb-4 !p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                    <div class="min-w-[200px] flex-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200" for="buscar">
                            Buscar por nombre
                        </label>
                        <form class="mt-1 flex gap-2" @submit.prevent="aplicarBusqueda">
                            <input
                                id="buscar"
                                v-model="textoBusqueda"
                                type="search"
                                placeholder="Ej. Luke"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            />
                            <button type="submit" :class="btnPrimario">Buscar</button>
                        </form>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200" for="filtro-genero">
                            Género
                        </label>
                        <select
                            id="filtro-genero"
                            :class="['mt-1', selectBase]"
                            :value="props.filtros.genero"
                            @change="cambiarGenero"
                        >
                            <option value="">Todos</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="n/a">n/a</option>
                        </select>
                    </div>
                </div>
            </CardPanel>

            <CardPanel class="!p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead :class="tablaCabecera">
                            <tr>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('nombre')">
                                        Nombre{{ indicadorOrden('nombre') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('altura')">
                                        Altura{{ indicadorOrden('altura') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('masa')">
                                        Masa{{ indicadorOrden('masa') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">
                                    <button type="button" class="font-semibold hover:underline" @click="ordenarPor('genero')">
                                        Género{{ indicadorOrden('genero') }}
                                    </button>
                                </th>
                                <th class="px-4 py-3">Origen</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="personaje in personajes.data" :key="personaje.id" :class="filaTabla">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ personaje.nombre }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ personaje.altura ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ personaje.masa ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ personaje.genero }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-2 py-0.5 text-xs font-medium"
                                        :class="
                                            personaje.origen_swapi
                                                ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200'
                                                : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200'
                                        "
                                    >
                                        {{ etiquetaOrigen(personaje.origen_swapi) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Link :href="`/personajes/${personaje.id}/editar`" :class="btnEnlace">Editar</Link>
                                    <button type="button" :class="[btnEnlacePeligro, 'ms-3']" @click="pedirEliminar(personaje)">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="personajes.data.length === 0">
                                <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    No hay personajes con estos filtros.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardPanel>

            <div v-if="personajes.links.length > 3" class="mt-4 flex flex-wrap justify-center gap-1">
                <template v-for="(link, i) in personajes.links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="rounded-lg border px-3 py-1 text-sm transition"
                        :class="
                            link.active
                                ? 'border-indigo-600 bg-indigo-600 text-white'
                                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700'
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
</template>