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

function formatPrice(price, discount = 0) {
    if (discount > 0) {
        price = price - (price * discount) / 100;
    }

    return price.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });
}
</script>

<template>
    <Link :href="route('product.show', { slug: props.slug })">
        <div
            class="bg-white rounded-xl outline outline-2 shadow-sm outline-gray-100 hover:outline-[#F8E4F3] hover:outline-2 transition duration-300 cursor-pointer h-full hover:scale-[1.02] hover:shadow-md"
        >
            <img
                v-if="props.image"
                :src="props.image"
                :alt="props.name"
                class="object-cover w-full rounded-t-lg aspect-square"
            />
            <div
                class="flex flex-col justify-between gap-1 px-3 py-4 md:pb-5 md:pt-4 md:px-5"
            >
                <h3
                    class="font-bold sm:text-lg line-clamp-2 overflow-ellipsis"
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
                    <div>
                        <p class="font-semibold sm:text-lg text-primary">
                            {{ formatPrice(props.price, props.discount) }}
                        </p>

                        <p
                            v-if="props.discount"
                            class="text-sm text-gray-500 line-through"
                        >
                            {{ formatPrice(props.price) }}
                        </p>
                    </div>

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
