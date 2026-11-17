<script setup>
import { Head } from '@inertiajs/vue3';
import PersonajeForm from '../../Components/PersonajeForm.vue';
</script>

<template>
    <Head title="Crear personaje" />

    <main class="mx-auto max-w-2xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold">Crear personaje</h1>

        <div class="rounded-xl bg-white p-6 shadow">
            <PersonajeForm url="/personajes" texto-boton="Guardar" />
        </div>
    </main>
</template>
