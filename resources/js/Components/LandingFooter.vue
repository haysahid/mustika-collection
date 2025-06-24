<script setup>
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";

const page = usePage();
const store = page.props.store || {};

const isHome = route().current("home");
</script>

<template>
    <footer class="bg-primary px-6 pt-12 sm:pt-20 pb-6 sm:px-12 md:px-[100px]">
        <div class="mx-auto max-w-7xl">
            <div
                class="flex flex-col items-start justify-between gap-8 mb-12 sm:flex-row"
            >
                <!-- Identity -->
                <div
                    class="flex flex-col items-center w-full text-center md:text-start sm:2/3 md:w-1/3 sm:items-start"
                >
                    <Link :href="route('home')">
                        <img
                            src="/storage/logo_yellow.png"
                            alt="Logo"
                            class="h-24 mb-8"
                        />
                    </Link>
                    <div class="text-center sm:text-start">
                        <p v-if="store.email" class="mb-2 text-gray-100">
                            <a
                                :href="`mailto:${store.email}`"
                                class="text-gray-100 hover:text-white"
                            >
                                {{ store.email }}
                            </a>
                        </p>
                        <p v-if="store.phone" class="mb-2 text-gray-100">
                            <a
                                href="tel:+6281234567890"
                                class="text-gray-100 hover:text-white"
                            >
                                {{ store.phone }}
                            </a>
                        </p>
                        <p v-if="store.address" class="text-gray-100">
                            {{ store.address }}
                        </p>
                    </div>
                </div>

                <!-- Quick Access -->
                <div
                    class="flex flex-wrap justify-center w-full grid-cols-1 gap-y-8 gap-x-12 sm:grid md:grid-cols-3 sm:1/3 md:w-2/3"
                >
                    <div class="text-center sm:text-start">
                        <h3 class="mb-4 text-lg font-semibold text-white/90">
                            Identitas Toko
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <NavLink
                                    :href="
                                        isHome
                                            ? '#advantages'
                                            : route('home') + '#advantages'
                                    "
                                    class="text-gray-100 hover:text-white"
                                >
                                    Keunggulan
                                </NavLink>
                            </li>
                            <li>
                                <NavLink
                                    :href="
                                        isHome
                                            ? '#certificates'
                                            : route('home') + '#certificates'
                                    "
                                    class="text-gray-100 hover:text-white"
                                >
                                    Sertifikasi
                                </NavLink>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center sm:text-start">
                        <NavLink :href="route('catalog')">
                            <h3
                                class="mb-4 text-lg font-semibold text-white/90 hover:text-white"
                            >
                                Katalog Produk
                            </h3>
                        </NavLink>
                        <ul class="space-y-2">
                            <li>
                                <NavLink
                                    :href="
                                        route('catalog', {
                                            categories: 'Gamis',
                                        })
                                    "
                                    class="text-gray-100 hover:text-white"
                                >
                                    Gamis
                                </NavLink>
                            </li>
                            <li>
                                <NavLink
                                    :href="
                                        route('catalog', {
                                            categories: 'Aksesoris',
                                        })
                                    "
                                    class="text-gray-100 hover:text-white"
                                >
                                    Aksesoris
                                </NavLink>
                            </li>
                            <li>
                                <NavLink
                                    :href="
                                        route('catalog', {
                                            categories: 'Baju Anak',
                                        })
                                    "
                                    class="text-gray-100 hover:text-white"
                                >
                                    Baju Anak
                                </NavLink>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center sm:text-start">
                        <h3 class="mb-4 text-lg font-semibold text-white/90">
                            Gabung
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <NavLink
                                    href="#about"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Agen
                                </NavLink>
                            </li>
                            <li>
                                <NavLink
                                    :href="route('home')"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Reseller
                                </NavLink>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-100 sm:text-base">
                    &copy; {{ new Date().getFullYear() }} {{ store.name }}. All
                    rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
