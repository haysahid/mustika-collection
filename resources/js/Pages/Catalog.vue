<script setup>
import LandingLayout from "@/Layouts/LandingLayout.vue";
import LandingSection from "@/Components/LandingSection.vue";
import ProductCard from "@/Components/ProductCard.vue";
import JoinUs from "@/Components/JoinUs.vue";
import CatalogFilter from "@/Components/CatalogFilter.vue";
</script>

<template>
    <LandingLayout>
        <!-- Search -->
        <LandingSection
            class="bg-gradient-to-b from-[#E0BEFF80] from-80% to-white min-h-[40vh] px-6 sm:px-12 md:px-[100px]"
        >
            <div
                class="flex flex-col items-center w-full py-12 text-center gap-9"
            >
                <div>
                    <h1 class="mb-4 text-4xl font-bold text-center">
                        Katalog Produk
                    </h1>
                    <p>Silahkan cari produk disini.</p>
                </div>
                <div class="w-full max-w-2xl mx-auto">
                    <form
                        action="#"
                        method="GET"
                        class="flex items-center space-x-4"
                    >
                        <label
                            for="search"
                            class="relative flex items-center w-full space-x-4"
                        >
                            <input
                                id="search"
                                type="text"
                                name="search"
                                placeholder="Cari produk..."
                                class="w-full py-4 pl-8 pr-24 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 overflow-ellipsis"
                            />
                            <button
                                type="submit"
                                class="absolute flex items-center justify-center px-4 py-2 text-white transition duration-200 bg-purple-600 rounded-lg hover:bg-purple-700 right-3"
                            >
                                Cari
                            </button>
                        </label>
                    </form>
                </div>
            </div>
        </LandingSection>

        <!-- Content -->
        <div class="p-6 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <LandingSection>
                <div
                    class="flex flex-col items-start justify-center mx-auto sm:flex-row gap-14 max-w-7xl"
                >
                    <!-- Filter -->
                    <CatalogFilter class="w-full sm:w-1/3" />

                    <!-- Products -->
                    <div class="flex flex-col items-start gap-12">
                        <div
                            class="grid w-full grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3"
                        >
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                            <ProductCard
                                name="Produk 1"
                                description="Deskripsi produk 1"
                                image="/storage/product/product_1.png"
                                :price="100000"
                                :discount="20"
                            />
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            <nav class="flex items-center gap-4 text-gray-600">
                                <a
                                    href="#"
                                    class="flex items-center justify-center px-6 text-white transition duration-200 border border-gray-300 rounded-lg aspect-square bg-primary"
                                    >1</a
                                >
                                <a
                                    href="#"
                                    class="px-6 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >2</a
                                >
                                <a
                                    href="#"
                                    class="px-6 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >3</a
                                >
                                <span>...</span>
                                <a
                                    href="#"
                                    class="px-6 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >6</a
                                >
                                <a
                                    href="#"
                                    class="px-6 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >{{ ">" }}</a
                                >
                            </nav>
                        </div>
                    </div>
                </div>
            </LandingSection>

            <!-- Join Us -->
            <LandingSection id="join">
                <JoinUs />
            </LandingSection>
        </div>
    </LandingLayout>
</template>
