<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ImportarModal from '../../Components/ImportarModal.vue';
import MensajeFlash from '../../Components/MensajeFlash.vue';
import Paginacion from '../../Components/Paginacion.vue';
import Spinner from '../../Components/Spinner.vue';

defineProps({
    personajes: { type: Object, required: true },
});

const modalAbierto = ref(false);
const idEliminando = ref(null);

const eliminar = (personaje) => {
    if (!confirm(`¿Seguro que deseas eliminar a ${personaje.nombre}?`)) {
        return;
    }

    router.delete(`/personajes/${personaje.id}`, {
        preserveScroll: true,
        onStart: () => (idEliminando.value = personaje.id),
        onFinish: () => (idEliminando.value = null),
    });
};

const formatearMedida = (valor, unidad) => (valor ? `${valor} ${unidad}` : '—');
</script>

<template>
    <Head title="Personajes" />

    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold">Personajes de Star Wars</h1>
            <div class="flex flex-col gap-2 sm:flex-row">
                <button
                    type="button"
                    class="rounded-lg bg-yellow-500 px-4 py-2 font-medium text-gray-900 hover:bg-yellow-400"
                    @click="modalAbierto = true"
                >
                    Importar desde SWAPI
                </button>
                <Link
                    href="/personajes/crear"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-center font-medium text-white hover:bg-blue-700"
                >
                    Crear Nuevo
                </Link>
            </div>
        </div>

        <MensajeFlash />

        <div v-if="personajes.data.length === 0" class="rounded-xl bg-white p-8 text-center text-gray-500 shadow">
            No hay personajes registrados.
        </div>

        <template v-else>
            <div class="hidden overflow-hidden rounded-xl bg-white shadow md:block">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Altura</th>
                            <th class="px-4 py-3">Masa</th>
                            <th class="px-4 py-3">Género</th>
                            <th class="px-4 py-3">Origen</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="personaje in personajes.data" :key="personaje.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ personaje.nombre }}</td>
                            <td class="px-4 py-3">{{ formatearMedida(personaje.altura, 'cm') }}</td>
                            <td class="px-4 py-3">{{ formatearMedida(personaje.masa, 'kg') }}</td>
                            <td class="px-4 py-3 capitalize">{{ personaje.genero }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                    :class="personaje.origen_swapi ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700'"
                                >
                                    {{ personaje.origen_swapi ? 'SWAPI' : 'Manual' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        :href="`/personajes/${personaje.id}/editar`"
                                        class="rounded-lg border border-gray-300 px-3 py-1 hover:bg-gray-100"
                                    >
                                        Editar
                                    </Link>
                                    <button
                                        type="button"
                                        :disabled="idEliminando === personaje.id"
                                        class="flex items-center gap-2 rounded-lg bg-red-600 px-3 py-1 text-white hover:bg-red-700 disabled:opacity-60"
                                        @click="eliminar(personaje)"
                                    >
                                        <Spinner v-if="idEliminando === personaje.id" />
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 md:hidden">
                <div v-for="personaje in personajes.data" :key="personaje.id" class="rounded-xl bg-white p-4 shadow">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="font-semibold">{{ personaje.nombre }}</h2>
                        <span
                            class="rounded-full px-2 py-1 text-xs font-medium"
                            :class="personaje.origen_swapi ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700'"
                        >
                            {{ personaje.origen_swapi ? 'SWAPI' : 'Manual' }}
                        </span>
                    </div>
                    <dl class="mt-2 grid grid-cols-3 gap-2 text-sm">
                        <div>
                            <dt class="text-gray-500">Altura</dt>
                            <dd>{{ formatearMedida(personaje.altura, 'cm') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Masa</dt>
                            <dd>{{ formatearMedida(personaje.masa, 'kg') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Género</dt>
                            <dd class="capitalize">{{ personaje.genero }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <Link
                            :href="`/personajes/${personaje.id}/editar`"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-center text-sm hover:bg-gray-100"
                        >
                            Editar
                        </Link>
                        <button
                            type="button"
                            :disabled="idEliminando === personaje.id"
                            class="flex items-center justify-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700 disabled:opacity-60"
                            @click="eliminar(personaje)"
                        >
                            <Spinner v-if="idEliminando === personaje.id" />
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <Paginacion :paginacion="personajes" />
            </div>
        </template>

        <ImportarModal :abierto="modalAbierto" @cerrar="modalAbierto = false" />
    </main>
</template>
