<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    name: String,
    slug: {
        type: String,
        default: "product-slug",
    },
    price: Number,
    image: String,
    description: String,
    discount: {
        type: Number,
        default: null,
    },
});
</script>

<template>
    <Link :href="route('product.show', { slug: props.slug })">
        <div
            class="bg-white shadow-md rounded-lg outline outline-1 outline-gray-100 hover:outline-[#F8E4F3] hover:outline-2 transition duration-300 cursor-pointer h-full hover:scale-105"
        >
            <img
                v-if="props.image"
                :src="props.image"
                :alt="props.name"
                class="object-cover w-full rounded-t-lg aspect-square"
            />
            <div class="px-4 pt-2.5 pb-4 md:px-6 md:py-4 flex flex-col gap-1">
                <h3
                    class="font-semibold sm:text-lg line-clamp-2 overflow-ellipsis"
                >
                    {{ props.name }}
                </h3>
                <p
                    class="text-sm text-gray-500 sm:text-base line-clamp-1 overflow-ellipsis"
                >
                    {{ props.description }}
                </p>
                <div
                    class="flex flex-wrap items-center justify-between mt-1 gap-y-1 gap-x-2"
                >
                    <p class="font-semibold sm:text-lg text-primary">
                        Rp {{ Intl.NumberFormat("id-ID").format(props.price) }}
                    </p>
                    <div
                        v-if="props.discount"
                        class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs text-white bg-red-500 rounded-md h-fit"
                    >
                        {{ props.discount }}%
                    </div>
                </div>
            </div>
        </div></Link
    >
</template>
