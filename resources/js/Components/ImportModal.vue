<script setup>
/**
 * Modal para importar por ID SWAPI; usa ModalBase y clases compartidas.
 */
import ModalBase from '@/Components/ModalBase.vue';
import { btnAdvertencia, btnSecundario, inputBase } from '@/lib/clasesUi';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    abierto: { type: Boolean, default: false },
});

const emit = defineEmits(['cerrar']);

const form = useForm({ swapi_id: '' });

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
</script>

<template>
    <ModalBase
        :abierto="abierto"
        titulo-id="importar-titulo"
        :deshabilitar-cerrar="form.processing"
        @cerrar="!form.processing && emit('cerrar')"
    >
        <h2 id="importar-titulo" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Importar desde SWAPI
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            ID entre 1 y 83 (por ejemplo, 1 = Luke Skywalker).
        </p>

        <form class="mt-4 space-y-3" @submit.prevent="importar">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200" for="swapi_id">
                    ID SWAPI
                </label>
                <input
                    id="swapi_id"
                    v-model="form.swapi_id"
                    type="number"
                    min="1"
                    max="83"
                    required
                    :class="inputBase"
                    :disabled="form.processing"
                />
                <p v-if="form.errors.swapi_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.swapi_id }}
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" :class="btnSecundario" :disabled="form.processing" @click="emit('cerrar')">
                    Cancelar
                </button>
                <button type="submit" :class="btnAdvertencia" :disabled="form.processing">
                    {{ form.processing ? 'Importando…' : 'Importar' }}
                </button>
            </div>
        </form>
    </ModalBase>
</template>