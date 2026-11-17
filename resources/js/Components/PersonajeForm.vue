<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import Spinner from './Spinner.vue';

const props = defineProps({
    personaje: { type: Object, default: () => ({}) },
    url: { type: String, required: true },
    metodo: { type: String, default: 'post' },
    textoBoton: { type: String, required: true },
});

const form = useForm({
    nombre: props.personaje.nombre ?? '',
    altura: props.personaje.altura ?? '',
    masa: props.personaje.masa ?? '',
    color_cabello: props.personaje.color_cabello ?? '',
    color_ojos: props.personaje.color_ojos ?? '',
    genero: props.personaje.genero ?? '',
    planeta_origen: props.personaje.planeta_origen ?? '',
});

const enviar = () => {
    form.submit(props.metodo, props.url);
};

const claseInput = 'mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200';
</script>

<template>
    <form class="grid grid-cols-1 gap-4 sm:grid-cols-2" @submit.prevent="enviar">
        <div class="sm:col-span-2">
            <label for="nombre" class="text-sm font-medium">Nombre *</label>
            <input id="nombre" v-model="form.nombre" type="text" required minlength="3" maxlength="100" :class="claseInput">
            <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</p>
        </div>

        <div>
            <label for="altura" class="text-sm font-medium">Altura (cm)</label>
            <input id="altura" v-model="form.altura" type="number" min="1" max="300" :class="claseInput">
            <p v-if="form.errors.altura" class="mt-1 text-sm text-red-600">{{ form.errors.altura }}</p>
        </div>

        <div>
            <label for="masa" class="text-sm font-medium">Masa (kg)</label>
            <input id="masa" v-model="form.masa" type="number" min="1" max="500" :class="claseInput">
            <p v-if="form.errors.masa" class="mt-1 text-sm text-red-600">{{ form.errors.masa }}</p>
        </div>

        <div>
            <label for="color_cabello" class="text-sm font-medium">Color de cabello</label>
            <input id="color_cabello" v-model="form.color_cabello" type="text" maxlength="100" :class="claseInput">
            <p v-if="form.errors.color_cabello" class="mt-1 text-sm text-red-600">{{ form.errors.color_cabello }}</p>
        </div>

        <div>
            <label for="color_ojos" class="text-sm font-medium">Color de ojos</label>
            <input id="color_ojos" v-model="form.color_ojos" type="text" maxlength="100" :class="claseInput">
            <p v-if="form.errors.color_ojos" class="mt-1 text-sm text-red-600">{{ form.errors.color_ojos }}</p>
        </div>

        <div>
            <label for="genero" class="text-sm font-medium">Género *</label>
            <select id="genero" v-model="form.genero" required :class="claseInput">
                <option value="" disabled>Selecciona un género</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="n/a">N/A</option>
            </select>
            <p v-if="form.errors.genero" class="mt-1 text-sm text-red-600">{{ form.errors.genero }}</p>
        </div>

        <div>
            <label for="planeta_origen" class="text-sm font-medium">Planeta de origen</label>
            <input id="planeta_origen" v-model="form.planeta_origen" type="text" maxlength="100" :class="claseInput">
            <p v-if="form.errors.planeta_origen" class="mt-1 text-sm text-red-600">{{ form.errors.planeta_origen }}</p>
        </div>

        <div class="flex flex-col-reverse gap-2 sm:col-span-2 sm:flex-row sm:justify-end">
            <Link href="/personajes" class="rounded-lg border border-gray-300 px-4 py-2 text-center hover:bg-gray-50">
                Cancelar
            </Link>
            <button
                type="submit"
                :disabled="form.processing"
                class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 disabled:opacity-60"
            >
                <Spinner v-if="form.processing" />
                {{ textoBoton }}
            </button>
        </div>
    </form>
</template>
