<script setup>
import LandingLayout from "@/Layouts/LandingLayout.vue";
import LandingSection from "@/Components/LandingSection.vue";
import ProductCard from "@/Components/ProductCard.vue";
import JoinUs from "@/Components/JoinUs.vue";
import CatalogFilter from "@/Components/CatalogFilter.vue";
import { ref, onMounted, watch, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";

const props = defineProps({
    products: null,
    filters: null,
});

const products = props.products.data.map((product) => ({
    ...product,
    images: product.images.map((image) => ({
        ...image,
        image: image.image ? "/storage/" + image.image : null,
    })),
}));

const catalogFilter = ref(null);

const filters = ref({
    brands: null,
    colors: null,
    categories: null,
    sizes: null,
});

const getFilters = computed(() => {
    return {
        brands:
            props.filters.brands.map((brand) => ({
                ...brand,
                selected: filters.value.brands?.includes(brand.name) || false,
            })) || [],
        colors:
            props.filters.colors.map((color) => ({
                ...color,
                selected: filters.value.colors?.includes(color.name) || false,
            })) || [],
        categories:
            props.filters.categories.map((category) => ({
                ...category,
                selected:
                    filters.value.categories?.includes(category.name) || false,
            })) || [],
        sizes:
            props.filters.sizes.map((size) => ({
                ...size,
                selected: filters.value.sizes?.includes(size.name) || false,
            })) || [],
    };
});

const getQueryParams = () => {
    filters.value.brands = route().params.brands
        ? route().params.brands.split(",")
        : [];
    filters.value.colors = route().params.colors
        ? route().params.colors.split(",")
        : [];
    filters.value.categories = route().params.categories
        ? route().params.categories.split(",")
        : [];
    filters.value.sizes = route().params.sizes
        ? route().params.sizes.split(",")
        : [];
};
getQueryParams();

watch(
    () => catalogFilter.value?.filters,
    (newFilters) => {
        filters.value = {
            brands:
                newFilters.brands
                    ?.filter((brand) => brand.selected)
                    .map((brand) => brand.name) || [],
            colors:
                newFilters.colors
                    ?.filter((color) => color.selected)
                    .map((color) => color.name) || [],
            categories:
                newFilters.categories
                    ?.filter((category) => category.selected)
                    .map((category) => category.name) || [],
            sizes:
                newFilters.sizes
                    ?.filter((size) => size.selected)
                    .map((size) => size.name) || [],
        };

        let queryParams = {};
        if (filters.value.brands.length > 0) {
            queryParams.brands = filters.value.brands.join(",");
        }
        if (filters.value.colors.length > 0) {
            queryParams.colors = filters.value.colors.join(",");
        }
        if (filters.value.categories.length > 0) {
            queryParams.categories = filters.value.categories.join(",");
        }
        if (filters.value.sizes.length > 0) {
            queryParams.sizes = filters.value.sizes.join(",");
        }

        router.get(route().current(), queryParams, {
            preserveState: true,
            preserveScroll: true,
        });
    },
    { deep: true }
);
</script>

<template>
    <LandingLayout title="Katalog">
        <!-- Search -->
        <LandingSection
            class="bg-gradient-to-b from-[#E0BEFF80] from-80% to-white !min-h-[40vh] px-6 sm:px-12 md:px-[100px]"
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
        <div
            class="p-6 !pt-4 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20"
        >
            <LandingSection>
                <div
                    class="flex flex-col items-start justify-center mx-auto md:flex-row gap-14 max-w-7xl"
                >
                    <!-- Filter -->
                    <CatalogFilter
                        ref="catalogFilter"
                        :filters="getFilters"
                        class="w-full md:w-1/3 lg:w-1/5"
                    />

                    <!-- Products -->
                    <div
                        class="flex flex-col items-start w-full gap-12 md:w-2/3 lg:w-4/5"
                    >
                        <div
                            class="grid w-full grid-cols-2 gap-5 sm:gap-6 lg:grid-cols-3"
                        >
                            <ProductCard
                                v-for="product in products"
                                :key="product.id"
                                :name="product.name"
                                :description="product.brand?.name"
                                :image="product.images[0]?.image"
                                :price="product.selling_price"
                                :discount="product.discount"
                                :slug="product.slug"
                            />
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            <nav class="flex items-center gap-4 text-gray-600">
                                <a
                                    href="#"
                                    class="flex items-center justify-center text-white transition duration-200 rounded-lg size-12 aspect-square bg-primary"
                                    >1</a
                                >
                                <a
                                    href="#"
                                    class="size-12 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >2</a
                                >
                                <a
                                    href="#"
                                    class="size-12 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >3</a
                                >
                                <span>...</span>
                                <a
                                    href="#"
                                    class="size-12 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                                    >6</a
                                >
                                <a
                                    href="#"
                                    class="size-12 aspect-square flex items-center justify-center bg-[#E4CFF6] text-primary rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
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
