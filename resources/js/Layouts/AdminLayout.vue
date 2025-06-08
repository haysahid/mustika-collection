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
                    class="flex-1 md:ml-64 transition-all duration-300 ease-in-out w-full"
                >
                    <main class="p-6">
                        <slot />
                    </main>

                    <footer
                        class="bg-secondary px-6 py-2 text-center absolute bottom-0 w-full md:w-[calc(100%-256px)]"
                    >
                        <p class="text-sm md:text-md text-gray-600">
                            © 2000 - Company, Inc. All rights reserved.
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>
