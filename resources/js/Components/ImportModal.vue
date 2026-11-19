<script setup>
/**
 * Modal para ingresar el ID SWAPI (1–83) y disparar la importación.
 */
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    abierto: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['cerrar']);

const form = useForm({
    swapi_id: '',
});

// Limpia el formulario al abrir de nuevo el modal.
watch(
    () => props.abierto,
    (valor) => {
        if (valor) {
            form.reset();
            form.clearErrors();
        }
    },
);

function importar() {
    form.post('/personajes/importar', {
        preserveScroll: true,
        onSuccess: () => emit('cerrar'),
    });
}

function cerrar() {
    if (!form.processing) {
        emit('cerrar');
    }
}
</script>

<template>
    <div
        v-if="abierto"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="importar-titulo"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg" @click.stop>
            <h2 id="importar-titulo" class="text-lg font-semibold text-gray-900">Importar desde SWAPI</h2>
            <p class="mt-1 text-sm text-gray-600">
                Ingrese el ID del personaje en SWAPI (del 1 al 83). Ejemplo: 1 = Luke Skywalker.
            </p>

            <form class="mt-4 space-y-3" @submit.prevent="importar">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="swapi_id">ID SWAPI</label>
                    <input
                        id="swapi_id"
                        v-model="form.swapi_id"
                        type="number"
                        min="1"
                        max="83"
                        required
                        class="mt-1 w-full rounded border-gray-300 shadow-sm"
                        :disabled="form.processing"
                    />
                    <p v-if="form.errors.swapi_id" class="mt-1 text-sm text-red-600">{{ form.errors.swapi_id }}</p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button
                        type="button"
                        class="rounded border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        :disabled="form.processing"
                        @click="cerrar"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="rounded bg-amber-600 px-4 py-2 text-sm text-white hover:bg-amber-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Importando…' : 'Importar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>