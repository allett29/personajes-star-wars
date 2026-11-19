<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { card, enlaceAccion, pageContainer, pageWrapper, tituloPagina } from '@/lib/clasesUi';
import { Link, useForm } from '@inertiajs/vue3';

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
    <AppLayout>
        <div :class="pageWrapper">
            <div :class="pageContainer" class="max-w-xl">
                <div class="mb-6 flex items-center justify-between">
                    <h1 :class="tituloPagina">Editar personaje</h1>
                    <Link href="/personajes" :class="enlaceAccion">Volver al listado</Link>
                </div>
                <div :class="card">
                    <PersonajeForm :form="form" submit-label="Actualizar" @submit="actualizar" />
                    <Link href="/personajes" class="mt-4 inline-block text-sm text-gray-600 hover:underline dark:text-gray-400">
                        Cancelar
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>