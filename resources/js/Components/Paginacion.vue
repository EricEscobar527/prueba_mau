<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    paginacion: { type: Object, required: true },
});

const enlaces = computed(() => {
    const links = props.paginacion.links;

    return links.map((link, indice) => {
        if (indice === 0) {
            return { ...link, label: 'Anterior' };
        }

        if (indice === links.length - 1) {
            return { ...link, label: 'Siguiente' };
        }

        return link;
    });
});
</script>

<template>
    <div v-if="paginacion.last_page > 1" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-gray-600">
            Mostrando {{ paginacion.from }} a {{ paginacion.to }} de {{ paginacion.total }} personajes
        </p>

        <nav class="flex flex-wrap justify-center gap-1">
            <template v-for="(enlace, indice) in enlaces" :key="indice">
                <Link
                    v-if="enlace.url"
                    :href="enlace.url"
                    preserve-scroll
                    class="rounded-lg border px-3 py-1 text-sm"
                    :class="enlace.active ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white hover:bg-gray-50'"
                >
                    {{ enlace.label }}
                </Link>
                <span v-else class="rounded-lg border border-gray-200 px-3 py-1 text-sm text-gray-400">
                    {{ enlace.label }}
                </span>
            </template>
        </nav>
    </div>
</template>
