<script setup>
const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
    valueClass: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div class="flex items-center justify-between gap-2">
        <p class="text-gray-700">{{ props.label }}</p>
        <p
            class="font-semibold text-right text-gray-700"
            :class="[props.valueClass]"
        >
            {{ props.value }}
        </p>
    </div>
</template>
