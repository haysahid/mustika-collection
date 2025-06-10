<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import AdminHeader from "@/Components/AdminHeader.vue";
import AdminSidebar from "@/Components/AdminSidebar.vue";

defineProps({
    title: String,
    showTitle: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="h-screen">
            <AdminHeader />

            <!-- Page Content -->
            <div class="flex">
                <!-- Sidebar -->
                <AdminSidebar />

                <div
                    class="flex-1 w-full transition-all duration-300 ease-in-out md:ml-64 h-[calc(100vh-72px)] sm:min-h-[calc(100vh-72px)] relative mt-[72px]"
                >
                    <main class="p-6 pb-16">
                        <div
                            v-if="showTitle"
                            class="flex items-center gap-4 mb-6 max-w-7xl"
                        >
                            <slot name="icon" />
                            <h1 class="text-xl font-bold">
                                {{ title }}
                            </h1>
                        </div>
                        <slot />
                    </main>

                    <footer
                        class="bg-secondary px-6 py-2 text-center fixed bottom-0 w-full md:w-[calc(100%-256px)]"
                    >
                        <p class="text-sm text-gray-600 md:text-md">
                            © 2000 - Company, Inc. All rights reserved.
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>
