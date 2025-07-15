<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center justify-center px-4 py-2 font-semibold text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-primary hover:bg-primary/80 focus:bg-primary-dark active:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50"
    >
        <slot />
    </button>
</template>
