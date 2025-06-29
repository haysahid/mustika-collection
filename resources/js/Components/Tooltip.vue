<script setup>
import { initFlowbite } from "flowbite";
import { onMounted } from "vue";

const props = defineProps({
    id: {
        type: String,
        default: "tooltip-default",
    },
    placement: {
        type: String,
        default: "top",
    },
});

onMounted(() => {
    initFlowbite();
});
</script>

<template>
    <div
        :data-tooltip-target="props.id"
        :data-tooltip-placement="props.placement"
    >
        <slot></slot>
        <div
            :id="props.id"
            role="tooltip"
            class="absolute z-10 invisible inline-block max-w-xs px-3 py-2 text-sm transition-opacity duration-300 rounded-lg shadow-xs opacity-0 text-primary bg-secondary tooltip dark:bg-secondary dark:text-gray-800 dark:shadow-lg"
        >
            <slot name="content"></slot>
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    </div>
</template>
