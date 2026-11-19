<script setup>
/** Página para crear un personaje a mano. */
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { tarjeta } from '@/lib/clasesUi.js';
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout });

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
    <Head title="Crear personaje" />

    <div class="mx-auto max-w-2xl">
        <PageHeader titulo="Crear personaje" descripcion="Los campos marcados con * son obligatorios." />
        <div :class="[tarjeta, 'p-6']">
            <PersonajeForm :form="form" texto-boton="Guardar" @enviar="guardar" />
        </div>
    </div>
</template>
