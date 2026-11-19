<script setup>
/**
 * Modal para importar un personaje desde SWAPI escribiendo su ID (1 a 83).
 * Los errores (ID repetido, API caída, etc.) se muestran debajo del campo.
 * El mensaje de éxito llega como toast desde el servidor.
 */
import FormField from '@/Components/FormField.vue';
import ModalBase from '@/Components/ModalBase.vue';
import Spinner from '@/Components/Spinner.vue';
import { btnPrimario, btnSecundario, inputBase } from '@/lib/clasesUi.js';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';

const props = defineProps({
    abierto: { type: Boolean, default: false },
});

const emit = defineEmits(['cerrar']);

const form = useForm({ swapi_id: '' });
const campoId = ref(null);

// Cada vez que se abre: formulario limpio y cursor en el campo.
watch(
    () => props.abierto,
    async (abierto) => {
        if (abierto) {
            form.reset();
            form.clearErrors();
            await nextTick();
            campoId.value?.focus();
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
    <ModalBase :abierto="abierto" titulo-id="importar-titulo" :bloquear-cierre="form.processing" @cerrar="emit('cerrar')">
        <h2 id="importar-titulo" class="font-titulo text-lg font-semibold text-slate-900 dark:text-white">
            Importar desde SWAPI
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
            Escribe el ID del personaje en SWAPI, del 1 al 83. Por ejemplo, 1 es Luke Skywalker y 4 es Darth Vader.
        </p>

        <form class="mt-5 space-y-5" @submit.prevent="importar">
            <FormField etiqueta="ID en SWAPI" para="swapi_id" :error="form.errors.swapi_id" requerido>
                <input
                    id="swapi_id"
                    ref="campoId"
                    v-model="form.swapi_id"
                    type="number"
                    min="1"
                    max="83"
                    required
                    :class="inputBase"
                    :disabled="form.processing"
                />
            </FormField>

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" :class="btnSecundario" :disabled="form.processing" @click="emit('cerrar')">
                    Cancelar
                </button>
                <button type="submit" :class="btnPrimario" :disabled="form.processing">
                    <Spinner v-if="form.processing" />
                    {{ form.processing ? 'Importando…' : 'Importar' }}
                </button>
            </div>
        </form>
    </ModalBase>
</template>
