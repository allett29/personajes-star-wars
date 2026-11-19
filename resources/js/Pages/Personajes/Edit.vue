<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { btnEnlace } from '@/lib/clasesUi';
import { Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout });

const props = defineProps({
    personaje: { type: Object, required: true },
});

const form = useForm({
    nombre: props.personaje.nombre,
    altura: props.personaje.altura ?? '',
    masa: props.personaje.masa ?? '',
    color_cabello: props.personaje.color_cabello ?? '',
    color_ojos: props.personaje.color_ojos ?? '',
    genero: props.personaje.genero,
    planeta_origen: props.personaje.planeta_origen ?? '',
});

function actualizar() {
    form.put(`/personajes/${props.personaje.id}`);
}
</script>

<template>
    <div class="py-8">
        <div class="mx-auto max-w-xl px-4">
            <PageHeader :titulo="`Editar: ${personaje.nombre}`" />
            <CardPanel>
                <PersonajeForm :form="form" submit-label="Actualizar" @submit="actualizar" />
                <Link href="/personajes" :class="[btnEnlace, 'mt-6 inline-block']">Volver al listado</Link>
            </CardPanel>
        </div>
    </div>
</template>