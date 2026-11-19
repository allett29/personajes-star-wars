<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { card, enlaceAccion, pageContainer, pageWrapper, tituloPagina } from '@/lib/clasesUi';
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
    <AppLayout>
        <div :class="pageWrapper">
            <div :class="pageContainer" class="max-w-xl">
                <div class="mb-6 flex items-center justify-between">
                    <h1 :class="tituloPagina">Nuevo personaje</h1>
                    <Link href="/personajes" :class="enlaceAccion">Volver al listado</Link>
                </div>
                <div :class="card">
                    <PersonajeForm :form="form" submit-label="Guardar" @submit="guardar" />
                    <Link href="/personajes" class="mt-4 inline-block text-sm text-gray-600 hover:underline dark:text-gray-400">
                        Cancelar
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>