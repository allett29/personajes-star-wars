<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';
import { btnEnlace } from '@/lib/clasesUi';
import { Link, useForm } from '@inertiajs/vue3';

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
    <div class="py-8">
        <div class="mx-auto max-w-xl px-4">
            <PageHeader titulo="Nuevo personaje" descripcion="Alta manual (origen Manual)." />
            <CardPanel>
                <PersonajeForm :form="form" submit-label="Guardar" @submit="guardar" />
                <Link href="/personajes" :class="[btnEnlace, 'mt-6 inline-block']">Volver al listado</Link>
            </CardPanel>
        </div>
    </div>
</template>