<script setup>
/**
 * Formulario de personaje compartido por las páginas Crear y Editar.
 * Recibe el `form` de useForm() y emite "enviar" al guardar.
 * Si hay cambios sin guardar, "Cancelar" pide confirmación antes de salir.
 */
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import FormField from '@/Components/FormField.vue';
import Spinner from '@/Components/Spinner.vue';
import { btnPrimario, btnSecundario, inputBase } from '@/lib/clasesUi.js';
import { GENEROS } from '@/lib/opciones.js';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    form: { type: Object, required: true },
    textoBoton: { type: String, default: 'Guardar' },
    urlCancelar: { type: String, default: '/personajes' },
});

const emit = defineEmits(['enviar']);

const confirmarSalida = ref(false);

function cancelar() {
    if (props.form.isDirty) {
        confirmarSalida.value = true;
        return;
    }
    router.visit(props.urlCancelar);
}

function salirSinGuardar() {
    confirmarSalida.value = false;
    router.visit(props.urlCancelar);
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="emit('enviar')">
        <FormField etiqueta="Nombre" para="nombre" :error="form.errors.nombre" requerido>
            <input id="nombre" v-model="form.nombre" type="text" required minlength="3" maxlength="100" :class="inputBase" />
        </FormField>

        <div class="grid gap-5 sm:grid-cols-2">
            <FormField etiqueta="Altura (cm)" para="altura" :error="form.errors.altura">
                <input id="altura" v-model="form.altura" type="number" min="1" max="300" step="1" :class="inputBase" />
            </FormField>

            <FormField etiqueta="Masa (kg)" para="masa" :error="form.errors.masa">
                <input id="masa" v-model="form.masa" type="number" min="1" max="500" step="1" :class="inputBase" />
            </FormField>

            <FormField etiqueta="Color de cabello" para="color_cabello" :error="form.errors.color_cabello">
                <input id="color_cabello" v-model="form.color_cabello" type="text" maxlength="100" :class="inputBase" />
            </FormField>

            <FormField etiqueta="Color de ojos" para="color_ojos" :error="form.errors.color_ojos">
                <input id="color_ojos" v-model="form.color_ojos" type="text" maxlength="100" :class="inputBase" />
            </FormField>

            <FormField etiqueta="Género" para="genero" :error="form.errors.genero" requerido>
                <select id="genero" v-model="form.genero" required :class="inputBase">
                    <option value="" disabled>Selecciona una opción</option>
                    <option v-for="opcion in GENEROS" :key="opcion.valor" :value="opcion.valor">{{ opcion.texto }}</option>
                </select>
            </FormField>

            <FormField etiqueta="Planeta de origen" para="planeta_origen" :error="form.errors.planeta_origen">
                <input id="planeta_origen" v-model="form.planeta_origen" type="text" maxlength="100" :class="inputBase" />
            </FormField>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 dark:border-espacio-800 sm:flex-row sm:justify-end">
            <button type="button" :class="btnSecundario" :disabled="form.processing" @click="cancelar">Cancelar</button>
            <button type="submit" :class="btnPrimario" :disabled="form.processing">
                <Spinner v-if="form.processing" />
                {{ textoBoton }}
            </button>
        </div>
    </form>

    <ConfirmDialog
        :abierto="confirmarSalida"
        tipo="aviso"
        titulo="¿Salir sin guardar?"
        mensaje="Los cambios que hiciste en este formulario se perderán."
        texto-confirmar="Salir sin guardar"
        @confirmar="salirSinGuardar"
        @cancelar="confirmarSalida = false"
    />
</template>
