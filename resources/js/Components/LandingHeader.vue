<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

const menus = [
    {
        name: "Tentang Kami",
        href: route().current("home") ? "#about" : route("home") + "#about",
        active: route().current("home"),
    },
    {
        name: "Katalog",
        href: route("catalog"),
        active: route().current("catalog"),
    },
    {
        name: "Gabung",
        href: "#join",
        active: false,
    },
];
</script>

<template>
    <nav class="sticky top-0 z-50 py-1 bg-primary sm:px-12 md:px-[100px]">
        <!-- Primary Navigation Menu -->
        <div class="px-6 mx-auto max-w-7xl sm:px-0">
            <div class="flex justify-between h-16">
                <div class="flex justify-between w-full">
                    <!-- Logo -->
                    <div class="flex items-center shrink-0">
                        <Link :href="route('home')">
                            <img
                                src="/storage/logo_white.png"
                                alt="Logo"
                                class="w-auto h-12 sm:h-16"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink
                            v-for="menu in menus"
                            :key="menu.name"
                            :href="menu.href"
                            :active="menu.active"
                            :class="{
                                '!text-white': menu.active,
                            }"
                        >
                            {{ menu.name }}
                        </NavLink>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="flex items-center -me-2 sm:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 transition duration-150 ease-in-out rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:hover:bg-white/20 focus:text-white"
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                    >
                        <svg
                            class="size-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <ul>
                <li v-for="menu in menus" :key="menu.name">
                    <NavLink
                        :href="menu.href"
                        :active="menu.active"
                        active-class="!bg-secondary !border-[#CE4DB1] text-primary"
                        class="px-4 py-2.5 w-full bg-transparent hover:bg-secondary/10 hover:border-l-4 hover:border-[#CE4DB1] border-l-4 transition-all duration-300 ease-in-out border-primary"
                        :class="{
                            '!text-primary': menu.active,
                        }"
                    >
                        {{ menu.name }}
                    </NavLink>
                </li>
            </ul>
        </div>
    </nav>
</template>
