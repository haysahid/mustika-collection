<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-white hover:bg-primary/80 focus:bg-gray-700 active:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
