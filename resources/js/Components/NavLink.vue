<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
    activeClass: String,
});

function scrollToId(id) {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({
            behavior: "smooth",
            inline: "center",
            block: "center",
        });

        // Update the URL hash without jumping
        history.pushState(null, null, `#${id}`);
    }
}
</script>

<template>
    <button
        v-if="props.href && props.href.startsWith('#')"
        type="button"
        class="inline-flex items-center text-base font-medium leading-5 text-center transition duration-150 ease-in-out outline-none text-white/80 hover:text-white focus:text-white"
        :class="[active ? activeClass : '']"
        @click="scrollToId(props.href.replace('#', ''))"
    >
        <slot />
    </button>

    <Link
        v-else
        :href="href"
        class="inline-flex items-center text-base font-medium leading-5 text-center transition duration-150 ease-in-out outline-none text-white/80 hover:text-white focus:text-white"
        :class="[active ? activeClass : '']"
    >
        <slot />
    </Link>
</template>
