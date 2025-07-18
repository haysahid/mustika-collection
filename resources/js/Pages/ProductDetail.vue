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
import { usePage } from "@inertiajs/vue3";
import DiscountTag from "@/Components/DiscountTag.vue";
import TextInput from "@/Components/TextInput.vue";

const page = usePage();
if (page.props.flash.access_token) {
    localStorage.setItem("access_token", page.props.flash.access_token);
}

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
        const images = orderForm.value?.selectedVariant.images;
        return images.length > 0 ? images : props.product.images;
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
        ].filter(
            (img, idx, arr) =>
                arr.findIndex((i) => i.image === img.image) === idx
        );
    }

    return [
        ...props.product.images,
        ...(props.product.variants
            ?.flatMap((variant) => variant.images)
            .filter(
                (img, idx, arr) =>
                    arr.findIndex((i) => i.image === img.image) === idx
            ) || []),
    ].filter(
        (img, idx, arr) => arr.findIndex((i) => i.image === img.image) === idx
    );
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
                        {{
                            orderForm?.selectedVariant?.name ??
                            props.product.name
                        }}
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
                            <DiscountTag
                                v-if="props.product.discount"
                                :discount-type="props.product.discount_type"
                                :discount="props.product.discount"
                                class="!px-1.5 !py-0.5 !rounded-md !text-sm !font-medium"
                            />
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
                        <TextInput
                            placeholder="Cari produk..."
                            bgClass="bg-gray-100"
                            textClass="text-sm sm:text-base !ps-4"
                            class="relative w-full max-w-sm"
                            @keyup.enter="
                                (e) => {
                                    $inertia.get(route('catalog'), {
                                        search: e.target.value,
                                    });
                                }
                            "
                        >
                            <template #suffix>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    class="absolute -translate-y-1/2 fill-gray-400 right-3 top-1/2 size-5"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M11 17C11.7879 17 12.5681 16.8448 13.2961 16.5433C14.0241 16.2417 14.6855 15.7998 15.2426 15.2426C15.7998 14.6855 16.2417 14.0241 16.5433 13.2961C16.8448 12.5681 17 11.7879 17 11C17 10.2121 16.8448 9.43185 16.5433 8.7039C16.2417 7.97595 15.7998 7.31451 15.2426 6.75736C14.6855 6.20021 14.0241 5.75825 13.2961 5.45672C12.5681 5.15519 11.7879 5 11 5C9.4087 5 7.88258 5.63214 6.75736 6.75736C5.63214 7.88258 5 9.4087 5 11C5 12.5913 5.63214 14.1174 6.75736 15.2426C7.88258 16.3679 9.4087 17 11 17ZM11 19C13.1217 19 15.1566 18.1571 16.6569 16.6569C18.1571 15.1566 19 13.1217 19 11C19 8.87827 18.1571 6.84344 16.6569 5.34315C15.1566 3.84285 13.1217 3 11 3C8.87827 3 6.84344 3.84285 5.34315 5.34315C3.84285 6.84344 3 8.87827 3 11C3 13.1217 3.84285 15.1566 5.34315 16.6569C6.84344 18.1571 8.87827 19 11 19Z"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M15.3201 15.2903C15.5082 15.1035 15.7629 14.9991 16.0281 15C16.2933 15.0009 16.5472 15.1072 16.7341 15.2953L20.7091 19.2953C20.8908 19.4844 20.9909 19.7373 20.9879 19.9995C20.9849 20.2618 20.879 20.5123 20.6931 20.6972C20.5071 20.8822 20.256 20.9866 19.9937 20.9881C19.7315 20.9896 19.4791 20.8881 19.2911 20.7053L15.3161 16.7053C15.1291 16.5172 15.0245 16.2626 15.0253 15.9975C15.026 15.7323 15.1321 15.4783 15.3201 15.2913V15.2903Z"
                                    />
                                </svg>
                            </template>
                        </TextInput>
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
