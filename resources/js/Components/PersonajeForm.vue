<script setup>
/**
 * Formulario reutilizable para crear y editar personajes.
 * Recibe el objeto `form` de useForm() de Inertia y el texto del botón.
 */
defineProps({
    form: {
        type: Object,
        required: true,
    },
    submitLabel: {
        type: String,
        default: 'Guardar',
    },
});

defineEmits(['submit']);
</script>

<template>
    <form class="space-y-4" @submit.prevent="$emit('submit')">
        <div>
            <label class="block text-sm font-medium text-gray-700" for="nombre">Nombre *</label>
            <input
                id="nombre"
                v-model="form.nombre"
                type="text"
                class="mt-1 w-full rounded border-gray-300 shadow-sm"
                required
                minlength="3"
            />
            <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="altura">Altura (cm)</label>
                <input
                    id="altura"
                    v-model="form.altura"
                    type="number"
                    min="1"
                    max="300"
                    class="mt-1 w-full rounded border-gray-300 shadow-sm"
                />
                <p v-if="form.errors.altura" class="mt-1 text-sm text-red-600">{{ form.errors.altura }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="masa">Masa (kg)</label>
                <input
                    id="masa"
                    v-model="form.masa"
                    type="number"
                    min="1"
                    max="500"
                    class="mt-1 w-full rounded border-gray-300 shadow-sm"
                />
                <p v-if="form.errors.masa" class="mt-1 text-sm text-red-600">{{ form.errors.masa }}</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="color_cabello">Color de cabello</label>
                <input
                    id="color_cabello"
                    v-model="form.color_cabello"
                    type="text"
                    class="mt-1 w-full rounded border-gray-300 shadow-sm"
                />
                <p v-if="form.errors.color_cabello" class="mt-1 text-sm text-red-600">
                    {{ form.errors.color_cabello }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="color_ojos">Color de ojos</label>
                <input
                    id="color_ojos"
                    v-model="form.color_ojos"
                    type="text"
                    class="mt-1 w-full rounded border-gray-300 shadow-sm"
                />
                <p v-if="form.errors.color_ojos" class="mt-1 text-sm text-red-600">{{ form.errors.color_ojos }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700" for="genero">Género *</label>
            <select
                id="genero"
                v-model="form.genero"
                class="mt-1 w-full rounded border-gray-300 shadow-sm"
                required
            >
                <option value="">Seleccione…</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="n/a">n/a</option>
            </select>
            <p v-if="form.errors.genero" class="mt-1 text-sm text-red-600">{{ form.errors.genero }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700" for="planeta_origen">Planeta de origen</label>
            <input
                id="planeta_origen"
                v-model="form.planeta_origen"
                type="text"
                class="mt-1 w-full rounded border-gray-300 shadow-sm"
            />
            <p v-if="form.errors.planeta_origen" class="mt-1 text-sm text-red-600">
                {{ form.errors.planeta_origen }}
            </p>
        </div>

        <div class="flex gap-3 pt-2">
            <button
                type="submit"
                class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Guardando…' : submitLabel }}
            </button>
        </div>
    </form>
</template>