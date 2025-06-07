<script setup>
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";

const showingFilterDropdown = ref(false);
</script>

<template>
    <div>
        <!-- Primary Filter -->
        <div class="hidden sm:block">
            <h2 class="text-2xl font-bold mb-4">Kategori</h2>
            <div class="hidden sm:flex sm:flex-col gap-4">
                <div class="flex items-center gap-2">
                    <Checkbox id="filter-1" name="filter-1" />
                    <label for="filter-1">Filter 1</label>
                </div>
                <div class="flex items center gap-2">
                    <Checkbox id="filter-2" name="filter-2" />
                    <label for="filter-2">Filter 2</label>
                </div>
                <div class="flex items center gap-2">
                    <Checkbox id="filter-3" name="filter-3" />
                    <label for="filter-3">Filter 3</label>
                </div>
                <div class="flex items center gap-2">
                    <Checkbox id="filter-4" name="filter-4" />
                    <label for="filter-4">Filter 4</label>
                </div>
            </div>
        </div>

        <!-- Filter Dropdown -->
        <div
            class="w-full sm:hidden bg-white shadow-md rounded-lg outline outline-1 outline-gray-100"
        >
            <button
                class="text-gray-500 hover:text-gray-700 focus:outline-none flex gap-4 justify-between items-center w-full p-4"
                @click="showingFilterDropdown = !showingFilterDropdown"
            >
                <h2>Filter</h2>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16m-7 6h7"
                    />
                </svg>
            </button>

            <div
                class="px-4 pt-2.5 pb-4"
                :class="{ hidden: !showingFilterDropdown }"
            >
                <h2 class="text-xl font-bold mb-4">Kategori</h2>
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="filter-dropdown-1"
                            name="filter-dropdown-1"
                        />
                        <label for="filter-dropdown-1">Filter 1</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="filter-dropdown-2"
                            name="filter-dropdown-2"
                        />
                        <label for="filter-dropdown-2">Filter 2</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="filter-dropdown-3"
                            name="filter-dropdown-3"
                        />
                        <label for="filter-dropdown-3">Filter 3</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="filter-dropdown-4"
                            name="filter-dropdown-4"
                        />
                        <label for="filter-dropdown-4">Filter 4</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
