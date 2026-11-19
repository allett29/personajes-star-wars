<script setup>
/** Página para editar un personaje; el formulario llega pre-llenado. */
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { tarjeta } from '@/lib/clasesUi.js';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    personaje: { type: Object, required: true },
});

// Los campos vacíos se muestran como '' para que los inputs no reciban null.
const form = useForm({
    nombre: props.personaje.nombre,
    altura: props.personaje.altura ?? '',
    masa: props.personaje.masa ?? '',
    color_cabello: props.personaje.color_cabello ?? '',
    color_ojos: props.personaje.color_ojos ?? '',
    genero: props.personaje.genero,
    planeta_origen: props.personaje.planeta_origen ?? '',
});

const descripcion = computed(() =>
    props.personaje.origen_swapi
        ? `Importado desde SWAPI (ID ${props.personaje.swapi_id}).`
        : 'Creado manualmente.',
);

function actualizar() {
    form.put(`/personajes/${props.personaje.id}`);
}
</script>

<template>
    <Head :title="`Editar ${personaje.nombre}`" />

    <div class="mx-auto max-w-2xl">
        <PageHeader :titulo="`Editar ${personaje.nombre}`" :descripcion="descripcion" />
        <div :class="[tarjeta, 'p-6']">
            <PersonajeForm :form="form" texto-boton="Actualizar" @enviar="actualizar" />
        </div>
    </div>
</template>
