<script setup>
import { Head } from '@inertiajs/vue3';
import PersonajeForm from '../../Components/PersonajeForm.vue';

defineProps({
    personaje: { type: Object, required: true },
});
</script>

<template>
    <Head :title="`Editar ${personaje.nombre}`" />

    <main class="mx-auto max-w-2xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold">Editar personaje</h1>

        <div class="rounded-xl bg-white p-6 shadow">
            <PersonajeForm
                :personaje="personaje"
                :url="`/personajes/${personaje.id}`"
                metodo="put"
                texto-boton="Actualizar"
            />
        </div>
    </main>
</template>
