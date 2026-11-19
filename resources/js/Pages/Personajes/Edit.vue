<script setup>
// Formulario de edición; envía PUT a /personajes/{id}.
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    personaje: {
        type: Object,
        required: true,
    },
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
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-xl px-4">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Editar personaje</h1>
                <Link href="/personajes" class="text-sm text-indigo-600 hover:underline">Volver al listado</Link>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <PersonajeForm :form="form" submit-label="Actualizar" @submit="actualizar" />
                <Link href="/personajes" class="mt-4 inline-block text-sm text-gray-600 hover:underline">Cancelar</Link>
            </div>
        </div>
    </div>
</template>