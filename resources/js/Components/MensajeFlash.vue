<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const visible = ref(true);

const flash = computed(() => page.props.flash);

watch(flash, () => {
    visible.value = true;
});
</script>

<template>
    <div
        v-if="visible && (flash.success || flash.error)"
        class="mb-4 flex items-start justify-between gap-4 rounded-lg border px-4 py-3 text-sm"
        :class="flash.success
            ? 'border-green-200 bg-green-50 text-green-800'
            : 'border-red-200 bg-red-50 text-red-800'"
    >
        <span>{{ flash.success || flash.error }}</span>
        <button type="button" class="font-bold" @click="visible = false">&times;</button>
    </div>
</template>
