<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
    activeClass: String,
});
</script>

<template>
    <Link
        :href="href"
        class="inline-flex items-center text-base font-medium leading-5 text-center transition duration-150 ease-in-out outline-none text-white/80 hover:text-white focus:text-white"
        :class="[active ? activeClass : '']"
    >
        <slot />
    </Link>
</template>
