<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
    activeClass: String,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center text-base font-medium leading-5 text-gray-900 outline-none transition duration-150 ease-in-out text-center"
        : "inline-flex items-center text-base font-medium leading-5 text-white/80 hover:text-white focus:text-white outline-none focus:text-gray-700 transition duration-150 ease-in-out text-center";
});
</script>

<template>
    <Link :href="href" :class="[classes, active ? activeClass : '']">
        <slot />
    </Link>
</template>
