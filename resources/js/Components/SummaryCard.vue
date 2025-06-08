<script setup>
defineProps({
    title: String,
    value: String,
});
</script>

<template>
    <div
        class="bg-white p-6 flex flex-col gap-3.5 rounded-xl border border-secondary shadow-sm hover:shadow-xl transition-shadow duration-300 ease-in-out shadow-secondary/20 hover:shadow-secondary/50"
    >
        <div class="flex items-center gap-3.5">
            <slot name="icon"></slot>
            <h3 class="text-lg sm:text-xl font-bold text-gray-700">
                {{ title }}
            </h3>
        </div>
        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ value }}</p>
    </div>
</template>
