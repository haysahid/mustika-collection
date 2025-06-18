<script setup>
import { Link } from "@inertiajs/vue3";
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
                        <p class="mb-2 text-gray-100">
                            <a
                                href="mailto:hay.sahid@gmail.com"
                                class="text-gray-100 hover:text-white"
                            >
                                hay.sahid@gmail.com
                            </a>
                        </p>
                        <p class="mb-2 text-gray-100">
                            <a
                                href="tel:+6281234567890"
                                class="text-gray-100 hover:text-white"
                            >
                                +62 812-3456-7890
                            </a>
                        </p>
                        <p class="text-gray-100">
                            Jl. Contoh No. 123, Jakarta, Indonesia
                        </p>
                    </div>
                </div>

                <!-- Quick Access -->
                <div
                    class="flex flex-wrap justify-center w-full grid-cols-1 gap-y-8 gap-x-12 sm:grid md:grid-cols-3 sm:1/3 md:w-2/3"
                >
                    <div class="text-center sm:text-start">
                        <h3 class="mb-4 text-lg font-semibold text-white">
                            Navigasi
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <Link
                                    href="#about"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Tentang Kami
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('home')"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Katalog
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#join"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Gabung
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center sm:text-start">
                        <h3 class="mb-4 text-lg font-semibold text-white">
                            Navigasi
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <Link
                                    href="#about"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Tentang Kami
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('home')"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Katalog
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#join"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Gabung
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center sm:text-start">
                        <h3 class="mb-4 text-lg font-semibold text-white">
                            Navigasi
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <Link
                                    href="#about"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Tentang Kami
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('home')"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Katalog
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#join"
                                    class="text-gray-100 hover:text-white"
                                >
                                    Gabung
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-100 sm:text-base">
                    &copy; {{ new Date().getFullYear() }} Your Company. All
                    rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
