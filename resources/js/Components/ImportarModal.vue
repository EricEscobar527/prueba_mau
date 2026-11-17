<script setup>
import { useForm } from '@inertiajs/vue3';
import Spinner from './Spinner.vue';

defineProps({
    abierto: { type: Boolean, required: true },
});

const emit = defineEmits(['cerrar']);

const form = useForm({
    swapi_id: '',
});

const cerrar = () => {
    if (form.processing) {
        return;
    }

    form.reset();
    form.clearErrors();
    emit('cerrar');
};

const importar = () => {
    form.post('/personajes/importar', {
        preserveScroll: true,
        onSuccess: () => cerrar(),
    });
};
</script>

<template>
    <div v-if="abierto" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="cerrar">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
            <h2 class="text-lg font-semibold">Importar desde SWAPI</h2>
            <p class="mt-1 text-sm text-gray-600">Ingresa el ID del personaje en SWAPI (1 a 83).</p>

            <form class="mt-4" @submit.prevent="importar">
                <label for="swapi_id" class="text-sm font-medium">ID de SWAPI</label>
                <input
                    id="swapi_id"
                    v-model="form.swapi_id"
                    type="number"
                    min="1"
                    max="83"
                    required
                    :disabled="form.processing"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
                <p v-if="form.errors.swapi_id" class="mt-2 text-sm text-red-600">{{ form.errors.swapi_id }}</p>

                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        :disabled="form.processing"
                        class="rounded-lg border border-gray-300 px-4 py-2 hover:bg-gray-50 disabled:opacity-60"
                        @click="cerrar"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex items-center justify-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 font-medium text-gray-900 hover:bg-yellow-400 disabled:opacity-60"
                    >
                        <Spinner v-if="form.processing" />
                        {{ form.processing ? 'Consultando SWAPI...' : 'Importar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
