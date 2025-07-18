<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import OrderItemSmall from "./OrderItemSmall.vue";
import OrderStatusChip from "./OrderStatusChip.vue";

const props = defineProps({
    transaction: {
        type: Object as () => TransactionEntity,
        required: true,
    },
    showDivider: {
        type: Boolean,
        default: true,
    },
});

const items = props.transaction.items || [];

function formatPrice(price = 0) {
    return price.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });
}

const total = computed(() => {
    return (
        props.transaction.items.reduce((total, item) => {
            return total + item.subtotal;
        }, 0) + props.transaction.shipping_cost
    );
});
</script>

<template>
    <Link
        :href="route('my-order.detail', props.transaction.code)"
        class="flex flex-col items-start justify-between gap-4 p-4 transition-all duration-200 ease-in-out border-b rounded-lg sm:p-6 outline -outline-1 outline-gray-200 group hover:outline-primary hover:scale-[1.02]"
        :class="{
            'border-none': !props.showDivider,
        }"
    >
        <div class="flex flex-col w-full sm:gap-y-1">
            <div
                class="flex items-start justify-between sm:items-center gap-x-3"
            >
                <!-- Transaction Code -->
                <h3
                    class="text-lg font-bold text-gray-700 group-hover:text-primary"
                >
                    #{{ props.transaction.code }}
                </h3>

                <!-- Total Price -->
                <p class="text-base font-semibold text-primary sm:text-lg">
                    {{ formatPrice(total) }}
                </p>
            </div>

            <div class="flex items-center justify-between gap-x-2">
                <!-- Created At -->
                <p
                    class="text-sm font-medium text-gray-500 sm:text-base group-hover:text-primary"
                >
                    {{ $formatDate(props.transaction.created_at) }}
                </p>

                <!-- Status -->
                <OrderStatusChip
                    :status="props.transaction.status"
                    :label="props.transaction.status?.toUpperCase()"
                />
            </div>
        </div>

        <div class="w-full">
            <OrderItemSmall
                v-for="(item, index) in items"
                :key="index"
                :item="item"
                :showDivider="false"
            />
        </div>
    </Link>
</template>
