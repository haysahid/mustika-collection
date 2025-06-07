<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <nav class="bg-primary px-6 sm:px-12 md:px-[100px] py-1 sticky top-0 z-50">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between h-16">
                <div class="flex justify-between w-full">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <img
                                src="/storage/logo_white.png"
                                alt="Logo"
                                class="h-12 sm:h-16 w-auto"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink
                            href="#about"
                            :active="route().current('dashboard')"
                        >
                            Tentang Kami
                        </NavLink>
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Katalog
                        </NavLink>
                        <NavLink
                            href="#join"
                            :active="route().current('dashboard')"
                        >
                            Gabung
                        </NavLink>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:hover:bg-white/20 focus:text-white transition duration-150 ease-in-out"
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
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Tentang Kami
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Katalog
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Gabung
                </ResponsiveNavLink>
            </div>
        </div>
    </nav>
</template>
