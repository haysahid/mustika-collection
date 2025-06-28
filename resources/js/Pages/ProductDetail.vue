<script setup lang="ts">
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import LandingSection from "@/Components/LandingSection.vue";
import ProductCard from "@/Components/ProductCard.vue";
import JoinUs from "@/Components/JoinUs.vue";
import ProductSelectionForm from "./Product/ProductSelectionForm.vue";
import ProductGallery from "./Product/ProductGallery.vue";
import ProductDetailTable from "./Product/ProductDetailTable.vue";

const props = defineProps({
    product: {
        type: Object as () => ProductEntity,
        required: true,
    },
    accumulatedStock: {
        type: Number,
        default: 0,
    },
    minOrder: {
        type: Number,
        default: 1,
    },
    motifs: {
        type: Array as () => string[],
        default: () => [],
    },
    colors: {
        type: Array as () => ColorEntity[],
        default: () => [],
    },
    sizes: {
        type: Array as () => SizeEntity[],
        default: () => [],
    },
    relatedProducts: {
        type: Array as () => ProductEntity[],
        default: () => [],
    },
});

const tableRows = [
    {
        label: "Brand",
        value: props.product.brand?.name,
    },
    {
        label: "Kategori",
        value:
            props.product.categories
                ?.map((category) => category.name)
                .join(", ") || "Tidak ada kategori",
    },
    {
        label: "Warna",
        value:
            props.colors?.map((color) => color.name).join(", ") ||
            "Tidak ada warna",
    },
    {
        label: "Ukuran",
        value:
            props.sizes?.map((size) => size.name).join(", ") ||
            "Tidak ada ukuran",
    },
    {
        label: "Stok",
        value: props.accumulatedStock > 0 ? props.accumulatedStock : "Habis",
    },
    {
        label: "Min. Pemesanan",
        value: props.minOrder,
    },
];

const orderForm = ref(null);

function formatPrice(price = 0) {
    return price.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });
}

const basePrice = computed(() => {
    if (orderForm.value?.selectedVariant != null) {
        return formatPrice(orderForm.value.selectedVariant.base_selling_price);
    }

    const lowestPrice = props.product?.lowest_base_selling_price || 0;
    const highestPrice = props.product?.highest_base_selling_price || 0;

    return `${formatPrice(lowestPrice)} - ${formatPrice(highestPrice)}`;
});

const discount = computed(() => {
    if (orderForm.value?.selectedVariant != null) {
        return orderForm.value.selectedVariant.discount_type === "percentage"
            ? `${orderForm.value.selectedVariant.discount || 0}%`
            : formatPrice(orderForm.value.selectedVariant.discount || 0);
    }

    return props.product?.discount_type === "percentage"
        ? `${props.product?.discount || 0}%`
        : formatPrice(props.product?.discount || 0);
});

const finalPrice = computed(() => {
    if (orderForm.value?.selectedVariant != null) {
        return formatPrice(orderForm.value.selectedVariant.final_selling_price);
    }

    const lowestPrice = props.product?.lowest_final_selling_price || 0;
    const highestPrice = props.product?.highest_final_selling_price || 0;

    return `${formatPrice(lowestPrice)} - ${formatPrice(highestPrice)}`;
});

const images = computed(() => {
    if (orderForm.value?.selectedVariant != null) {
        return orderForm.value?.selectedVariant.images || [];
    }

    if (
        orderForm.value?.filter.motif != null ||
        orderForm.value?.filter.color != null ||
        orderForm.value?.filter.size != null
    ) {
        return [
            ...(orderForm.value?.filteredVariants
                ?.flatMap((variant) => variant.images)
                .filter(
                    (img, idx, arr) =>
                        arr.findIndex((i) => i.image === img.image) === idx
                ) || []),
            ...props.product.images,
        ];
    }

    return [
        ...props.product.images,
        ...(props.product.variants
            ?.flatMap((variant) => variant.images)
            .filter(
                (img, idx, arr) =>
                    arr.findIndex((i) => i.image === img.image) === idx
            ) || []),
    ];
});
</script>

<template>
    <LandingLayout title="Detail Produk">
        <div class="p-6 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <!-- Detail -->
            <div
                data-aos="fade-up"
                data-aos-duration="600"
                class="grid grid-cols-1 gap-8 mx-auto lg:grid-cols-2 lg:gap-14 max-w-7xl"
            >
                <ProductGallery
                    :images="images"
                    :altText="
                        orderForm?.selectedVariant?.name || props.product.name
                    "
                />

                <div class="flex flex-col justify-start py-4">
                    <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                        {{ props.product.name }}
                    </h1>
                    <div class="flex flex-col items-start gap-2.5 mb-6">
                        <p class="text-xl font-bold sm:text-2xl text-primary">
                            {{ finalPrice }}
                        </p>
                        <div
                            v-if="props.product.discount"
                            class="flex items-center gap-2"
                        >
                            <p
                                class="text-sm text-gray-500 line-through strike sm:text-base"
                            >
                                {{ basePrice }}
                            </p>
                            <div
                                class="bg-red-500 text-white px-1.5 py-0.5 rounded-md text-sm"
                            >
                                {{ discount }}
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <ProductSelectionForm
                            ref="orderForm"
                            :product="props.product"
                            :accumulated-stock="props.accumulatedStock"
                            :min-order="props.minOrder"
                            :motifs="props.motifs"
                            :colors="props.colors"
                            :sizes="props.sizes"
                        />

                        <!-- Table Details -->
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-700"
                            >
                                Rincian
                            </h3>
                            <div
                                class="relative overflow-x-auto rounded-lg w-full max-w-[600px] border border-gray-300"
                            >
                                <ProductDetailTable :rows="tableRows" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-700"
                            >
                                Deskripsi
                            </h3>
                            <p class="text-gray-700">
                                {{ props.product.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <LandingSection
                v-if="props.relatedProducts.length > 0"
                id="related-products"
            >
                <div
                    data-aos="fade-up"
                    data-aos-duration="600"
                    class="flex flex-col items-start justify-center gap-4 mx-auto max-w-7xl"
                >
                    <div class="flex items-center justify-between w-full gap-4">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Produk Terkait
                        </h1>
                        <Link
                            :href="route('catalog')"
                            class="flex items-center justify-center gap-1 text-sm text-primary hover:underline"
                        >
                            <p>Selengkapnya</p>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-4 pt-0.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"
                                />
                            </svg>
                        </Link>
                    </div>
                    <div
                        class="grid w-full grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 sm:gap-9"
                    >
                        <ProductCard
                            v-for="product in props.relatedProducts || []"
                            :key="product.id"
                            :name="product.name"
                            :basePrice="product.lowest_base_selling_price"
                            :discount="product.discount"
                            :finalPrice="product.lowest_final_selling_price"
                            :image="
                                product.images && product.images.length > 0
                                    ? '/storage/' + product.images[0].image
                                    : null
                            "
                            :description="product.brand?.name"
                            :slug="product.slug"
                        />
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
