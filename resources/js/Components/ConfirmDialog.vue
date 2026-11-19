<script setup>
/**
 * Diálogo de confirmación reutilizable (eliminar, salir sin guardar, etc.).
 * tipo "peligro": acción destructiva en rojo. tipo "aviso": advertencia en ámbar.
 */
import ModalBase from '@/Components/ModalBase.vue';
import Spinner from '@/Components/Spinner.vue';
import { btnPeligro, btnPrimario, btnSecundario } from '@/lib/clasesUi.js';
import { computed } from 'vue';

const props = defineProps({
    abierto: { type: Boolean, default: false },
    titulo: { type: String, required: true },
    mensaje: { type: String, required: true },
    textoConfirmar: { type: String, default: 'Confirmar' },
    tipo: { type: String, default: 'peligro' },
    cargando: { type: Boolean, default: false },
});

const emit = defineEmits(['confirmar', 'cancelar']);

const esPeligro = computed(() => props.tipo === 'peligro');
</script>

<template>
    <ModalBase :abierto="abierto" titulo-id="confirmar-titulo" :bloquear-cierre="cargando" @cerrar="emit('cancelar')">
        <div class="flex gap-4">
            <span
                class="grid h-10 w-10 shrink-0 place-items-center rounded-full text-lg font-bold"
                :class="
                    esPeligro
                        ? 'bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400'
                        : 'bg-amber-100 text-amber-600 dark:bg-amber-400/15 dark:text-amber-300'
                "
                aria-hidden="true"
            >
                !
            </span>
            <div>
                <h2 id="confirmar-titulo" class="font-titulo text-lg font-semibold text-slate-900 dark:text-white">
                    {{ titulo }}
                </h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ mensaje }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" :class="btnSecundario" :disabled="cargando" @click="emit('cancelar')">Cancelar</button>
            <button type="button" :class="esPeligro ? btnPeligro : btnPrimario" :disabled="cargando" @click="emit('confirmar')">
                <Spinner v-if="cargando" />
                {{ textoConfirmar }}
            </button>
        </div>
    </ModalBase>
</template>
