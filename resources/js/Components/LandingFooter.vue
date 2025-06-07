<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <footer class="bg-primary px-6 pt-20 pb-6 sm:px-12 md:px-[100px]">
        <div class="max-w-7xl mx-auto">
            <div
                class="flex flex-col sm:flex-row justify-between items-start gap-8 mb-12"
            >
                <!-- Identity -->
                <div
                    class="text-center md:text-start w-full sm:2/3 md:w-1/3 flex flex-col items-center sm:items-start"
                >
                    <Link :href="route('dashboard')">
                        <img
                            src="/storage/logo_yellow.png"
                            alt="Logo"
                            class="h-24 mb-8"
                        />
                    </Link>
                    <div class="text-center sm:text-start">
                        <p class="text-gray-100 mb-2">
                            <a
                                href="mailto:hay.sahid@gmail.com"
                                class="text-gray-100 hover:text-white"
                            >
                                hay.sahid@gmail.com
                            </a>
                        </p>
                        <p class="text-gray-100 mb-2">
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
                    class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full sm:1/3 md:w-2/3"
                >
                    <div class="text-center sm:text-start">
                        <h3 class="text-lg font-semibold text-white mb-4">
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
                                    :href="route('dashboard')"
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
                        <h3 class="text-lg font-semibold text-white mb-4">
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
                                    :href="route('dashboard')"
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
                        <h3 class="text-lg font-semibold text-white mb-4">
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
                                    :href="route('dashboard')"
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
                <p class="text-gray-100">
                    &copy; {{ new Date().getFullYear() }} Your Company. All
                    rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
