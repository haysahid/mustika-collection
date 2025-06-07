<script setup>
const props = defineProps({
    title: String,
    description: String,
});
</script>

<template>
    <div
        class="bg-[#E0BEFF80] shadow-md rounded-lg p-6 flex flex-col items-start gap-4"
    >
        <h2 class="text-xl font-bold text-primary">
            {{ props.title }}
        </h2>
        <p class="text-gray-700">
            {{ props.description }}
        </p>
    </div>
</template>
