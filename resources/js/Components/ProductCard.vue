<script setup>
const props = defineProps({
    name: String,
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
    <div
        class="bg-white shadow-md rounded-lg outline outline-1 outline-gray-100 hover:outline-[#F8E4F3] hover:outline-2 transition duration-300 cursor-pointer"
    >
        <img
            :src="props.image"
            alt="Product 1"
            class="w-full object-cover rounded-t-lg aspect-square"
        />
        <div class="px-4 py-2.5 md:px-6 md:py-4">
            <h3 class="text-lg font-semibold mb-2">{{ props.name }}</h3>
            <p class="text-gray-500 mb-2">
                {{ props.description }}
            </p>
            <div class="flex items-center justify-between">
                <p class="text-primary mb-2 text-lg font-semibold">
                    Rp {{ Intl.NumberFormat("id-ID").format(props.price) }}
                </p>
                <div
                    v-if="props.discount"
                    class="bg-red-500 text-white px-2 py-1 rounded-md text-xs"
                >
                    {{ props.discount }}%
                </div>
            </div>
        </div>
    </div>
</template>
