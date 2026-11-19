<script setup>
// Formulario de alta de personaje; envía POST a /personajes.
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    nombre: '',
    altura: '',
    masa: '',
    color_cabello: '',
    color_ojos: '',
    genero: '',
    planeta_origen: '',
});

function guardar() {
    form.post('/personajes');
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-xl px-4">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Nuevo personaje</h1>
                <Link href="/personajes" class="text-sm text-indigo-600 hover:underline">Volver al listado</Link>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <PersonajeForm :form="form" submit-label="Guardar" @submit="guardar" />
                <Link href="/personajes" class="mt-4 inline-block text-sm text-gray-600 hover:underline">Cancelar</Link>
            </div>
        </div>
    </div>
</template>