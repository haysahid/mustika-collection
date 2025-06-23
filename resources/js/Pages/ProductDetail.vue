<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import LandingSection from "@/Components/LandingSection.vue";
import ProductCard from "@/Components/ProductCard.vue";
import JoinUs from "@/Components/JoinUs.vue";

const page = usePage();
const store = page.props.store;

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const image = ref(
    props.product.images.length > 0 ? props.product.images[0] : null
);

const fullDescription = ref(false);

const imageIndex = ref(0);

const changeImage = (index) => {
    image.value = props.product.images[index];
    imageIndex.value = index;
};

const canGoToPreviousImage = computed(() => {
    return imageIndex.value > 0;
});

const canGoToNextImage = computed(() => {
    return imageIndex.value < props.product.images.length - 1;
});

const goToPreviousImage = () => {
    if (canGoToPreviousImage.value) {
        imageIndex.value--;
        image.value = props.product.images[imageIndex.value];
    }
};

const goToNextImage = () => {
    if (canGoToNextImage.value) {
        imageIndex.value++;
        image.value = props.product.images[imageIndex.value];
    }
};
</script>

<template>
    <LandingLayout title="Detail Produk">
        <div class="p-6 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <!-- Detail -->
            <div
                class="grid grid-cols-1 gap-8 mx-auto lg:grid-cols-2 lg:gap-14 max-w-7xl"
            >
                <div class="flex flex-col items-start justify-start gap-4">
                    <div
                        class="relative flex items-center justify-center w-full overflow-hidden group"
                    >
                        <img
                            :src="'/storage/' + image.image"
                            :alt="props.product.name"
                            class="object-cover rounded-2xl aspect-square"
                        />
                        <button
                            v-if="canGoToPreviousImage"
                            @click="goToPreviousImage"
                            type="button"
                            class="size-8 sm:size-12 opacity-0 aspect-square flex items-center justify-center text-primary hover:bg-[#E4CFF6]/80 font-semibold absolute -left-8 group-hover:bg-black/40 group-hover:opacity-100 group-hover:left-4 transition-all duration-300 ease-in-out rounded-full hover:scale-110"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                class="fill-gray-200 size-5 sm:size-6"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M11.7803 5.21967C12.0732 5.51256 12.0732 5.98744 11.7803 6.28033L8.06066 10L11.7803 13.7197C12.0732 14.0126 12.0732 14.4874 11.7803 14.7803C11.4874 15.0732 11.0126 15.0732 10.7197 14.7803L6.46967 10.5303C6.17678 10.2374 6.17678 9.76256 6.46967 9.46967L10.7197 5.21967C11.0126 4.92678 11.4874 4.92678 11.7803 5.21967Z"
                                />
                            </svg>
                        </button>
                        <button
                            v-if="canGoToNextImage"
                            @click="goToNextImage"
                            type="button"
                            class="size-8 sm:size-12 opacity-0 aspect-square flex items-center justify-center text-primary hover:bg-[#E4CFF6]/80 font-semibold absolute -right-8 group-hover:bg-black/40 group-hover:opacity-100 group-hover:right-4 transition-all duration-300 ease-in-out rounded-full hover:scale-110"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                class="fill-gray-200 size-5 sm:size-6"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M8.21967 5.21967C8.51256 4.92678 8.98744 4.92678 9.28033 5.21967L13.5303 9.46967C13.8232 9.76256 13.8232 10.2374 13.5303 10.5303L9.28033 14.7803C8.98744 15.0732 8.51256 15.0732 8.21967 14.7803C7.92678 14.4874 7.92678 14.0126 8.21967 13.7197L11.9393 10L8.21967 6.28033C7.92678 5.98744 7.92678 5.51256 8.21967 5.21967Z"
                                />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        <img
                            v-for="(img, index) in props.product.images"
                            :key="img.id"
                            :src="'/storage/' + img.image"
                            :alt="props.product.name"
                            class="w-[80px] sm:w-[120px] h-[60px] sm:h-[80px] object-cover rounded-lg cursor-pointer transition duration-200 hover:scale-105"
                            :class="{
                                'opacity-50 !cursor-default hover:!scale-100':
                                    image.id == img.id,
                            }"
                            @click="changeImage(index)"
                        />
                    </div>
                </div>
                <div class="flex flex-col justify-start py-6">
                    <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                        {{ props.product.name }}
                    </h1>
                    <div class="flex items-center gap-4 mb-6">
                        <p class="text-xl font-bold sm:text-2xl">
                            Rp
                            {{
                                props.product.selling_price.toLocaleString(
                                    "id-ID"
                                )
                            }}
                        </p>
                        <div
                            class="bg-red-500 text-white px-1.5 py-0.5 rounded-md text-sm"
                        >
                            {{ props.product.discount }}%
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <!-- Table Details -->
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-700"
                            >
                                Rincian
                            </h3>
                            <div
                                class="relative overflow-x-auto sm:rounded-lg w-full max-w-[400px] border border-gray-300"
                            >
                                <table class="w-full">
                                    <tbody
                                        class="font-semibold [&>tr]:border-b [&>tr]:border-gray-300 [&>tr>td]:p-2 [&>tr:last-child]:border-b-0 [&>tr>td:first-child]:bg-gray-100 [&>tr>td]:text-gray-600"
                                    >
                                        <tr>
                                            <td>Kategori</td>
                                            <td>
                                                {{
                                                    props.product.categories
                                                        ?.map(
                                                            (category) =>
                                                                category.name
                                                        )
                                                        .join(", ")
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Warna</td>
                                            <td>
                                                {{ props.product.color?.name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ukuran</td>
                                            <td>
                                                {{
                                                    props.product.sizes
                                                        ?.map(
                                                            (size) => size.name
                                                        )
                                                        .join(", ")
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Stok</td>
                                            <td>
                                                {{
                                                    props.product.stock > 0
                                                        ? "Tersedia"
                                                        : "Habis"
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Min. Pemesanan</td>
                                            <td>
                                                {{ props.product.min_order }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-700"
                            >
                                Deskripsi
                            </h3>
                            <p
                                class="text-gray-700 line-clamp-3 overflow-ellipsis"
                                :class="{
                                    'line-clamp-none overflow-visible':
                                        fullDescription,
                                }"
                            >
                                {{ props.product.description }}
                            </p>

                            <button
                                class="mt-2 text-primary hover:underline"
                                @click="fullDescription = !fullDescription"
                            >
                                {{
                                    fullDescription
                                        ? "Lihat Sedikit"
                                        : "Selengkapnya..."
                                }}
                            </button>
                        </div>

                        <!-- Call to Actions -->
                        <div
                            class="flex flex-col items-start gap-4 sm:flex-row sm:items-center"
                        >
                            <button
                                class="flex items-center justify-center w-full gap-2 px-6 py-3 transition duration-200 rounded-lg bg-primary hover:bg-primary-dark sm:w-auto"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="25"
                                    viewBox="0 0 24 25"
                                    class="fill-white"
                                >
                                    <path
                                        d="M17 18.6948C15.89 18.6948 15 19.5848 15 20.6948C15 21.2253 15.2107 21.734 15.5858 22.109C15.9609 22.4841 16.4696 22.6948 17 22.6948C17.5304 22.6948 18.0391 22.4841 18.4142 22.109C18.7893 21.734 19 21.2253 19 20.6948C19 20.1644 18.7893 19.6557 18.4142 19.2806C18.0391 18.9055 17.5304 18.6948 17 18.6948ZM1 2.69482V4.69482H3L6.6 12.2848L5.24 14.7348C5.09 15.0148 5 15.3448 5 15.6948C5 16.2253 5.21071 16.734 5.58579 17.109C5.96086 17.4841 6.46957 17.6948 7 17.6948H19V15.6948H7.42C7.3537 15.6948 7.29011 15.6685 7.24322 15.6216C7.19634 15.5747 7.17 15.5111 7.17 15.4448C7.17 15.3948 7.18 15.3548 7.2 15.3248L8.1 13.6948H15.55C16.3 13.6948 16.96 13.2748 17.3 12.6648L20.88 6.19482C20.95 6.03482 21 5.86482 21 5.69482C21 5.42961 20.8946 5.17525 20.7071 4.98772C20.5196 4.80018 20.2652 4.69482 20 4.69482H5.21L4.27 2.69482M7 18.6948C5.89 18.6948 5 19.5848 5 20.6948C5 21.2253 5.21071 21.734 5.58579 22.109C5.96086 22.4841 6.46957 22.6948 7 22.6948C7.53043 22.6948 8.03914 22.4841 8.41421 22.109C8.78929 21.734 9 21.2253 9 20.6948C9 20.1644 8.78929 19.6557 8.41421 19.2806C8.03914 18.9055 7.53043 18.6948 7 18.6948Z"
                                    />
                                </svg>
                                <p class="font-semibold text-white">
                                    Marketplace
                                </p>
                            </button>
                            <a
                                :href="`https://wa.me/${store.phone?.replace(
                                    /[^0-9]/g,
                                    ''
                                )}?text=Halo, saya tertarik dengan produk ${
                                    props.product.name
                                }.`"
                                target="_blank"
                                class="w-full sm:w-auto"
                            >
                                <button
                                    class="flex items-center justify-center w-full gap-2 px-6 py-3 transition duration-200 bg-green-600 rounded-lg hover:bg-green-700 sm:w-auto"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="25"
                                        viewBox="0 0 24 25"
                                        class="fill-white"
                                    >
                                        <path
                                            d="M19.0498 5.60488C18.1329 4.67898 17.0408 3.94484 15.8373 3.44524C14.6338 2.94564 13.3429 2.69056 12.0398 2.69488C6.5798 2.69488 2.1298 7.14488 2.1298 12.6049C2.1298 14.3549 2.5898 16.0549 3.4498 17.5549L2.0498 22.6949L7.2998 21.3149C8.7498 22.1049 10.3798 22.5249 12.0398 22.5249C17.4998 22.5249 21.9498 18.0749 21.9498 12.6149C21.9498 9.96488 20.9198 7.47488 19.0498 5.60488ZM12.0398 20.8449C10.5598 20.8449 9.1098 20.4449 7.8398 19.6949L7.5398 19.5149L4.4198 20.3349L5.2498 17.2949L5.0498 16.9849C4.22735 15.6719 3.79073 14.1542 3.7898 12.6049C3.7898 8.06488 7.4898 4.36488 12.0298 4.36488C14.2298 4.36488 16.2998 5.22488 17.8498 6.78488C18.6174 7.54874 19.2257 8.45742 19.6394 9.45821C20.0531 10.459 20.264 11.532 20.2598 12.6149C20.2798 17.1549 16.5798 20.8449 12.0398 20.8449ZM16.5598 14.6849C16.3098 14.5649 15.0898 13.9649 14.8698 13.8749C14.6398 13.7949 14.4798 13.7549 14.3098 13.9949C14.1398 14.2449 13.6698 14.8049 13.5298 14.9649C13.3898 15.1349 13.2398 15.1549 12.9898 15.0249C12.7398 14.9049 11.9398 14.6349 10.9998 13.7949C10.2598 13.1349 9.7698 12.3249 9.6198 12.0749C9.4798 11.8249 9.5998 11.6949 9.7298 11.5649C9.8398 11.4549 9.9798 11.2749 10.0998 11.1349C10.2198 10.9949 10.2698 10.8849 10.3498 10.7249C10.4298 10.5549 10.3898 10.4149 10.3298 10.2949C10.2698 10.1749 9.7698 8.95488 9.5698 8.45488C9.3698 7.97488 9.1598 8.03488 9.0098 8.02488H8.5298C8.3598 8.02488 8.0998 8.08488 7.8698 8.33488C7.6498 8.58488 7.0098 9.18488 7.0098 10.4049C7.0098 11.6249 7.89981 12.8049 8.0198 12.9649C8.1398 13.1349 9.7698 15.6349 12.2498 16.7049C12.8398 16.9649 13.2998 17.1149 13.6598 17.2249C14.2498 17.4149 14.7898 17.3849 15.2198 17.3249C15.6998 17.2549 16.6898 16.7249 16.8898 16.1449C17.0998 15.5649 17.0998 15.0749 17.0298 14.9649C16.9598 14.8549 16.8098 14.8049 16.5598 14.6849Z"
                                        />
                                    </svg>
                                    <p class="font-semibold text-white">
                                        WhatsApp
                                    </p>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <LandingSection>
                <div
                    class="flex flex-col items-start justify-center gap-4 mx-auto max-w-7xl"
                >
                    <div class="flex items-center justify-between w-full gap-4">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Produk Terkait
                        </h1>
                        <a
                            href="#"
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
                        </a>
                    </div>
                    <div
                        class="grid w-full grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 sm:gap-9"
                    >
                        <ProductCard
                            v-for="product in props.relatedProducts || []"
                            :key="product.id"
                            :name="product.name"
                            :price="product.selling_price"
                            :image="'/storage/' + product.images[0].image"
                            :description="product.brand?.name"
                            :discount="product.discount"
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
