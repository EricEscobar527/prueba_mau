<script setup>
defineProps({ mensaje: String });
</script>

<template>
    <h1 class="p-8 text-3xl font-bold text-blue-600">{{ mensaje }}</h1>
</template>
