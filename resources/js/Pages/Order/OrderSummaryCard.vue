<script setup lang="ts">
import OrderContentRow from "@/Components/OrderContentRow.vue";
import OrderStatusChip from "./OrderStatusChip.vue";

const props = defineProps({
    invoice: {
        type: Object as () => InvoiceEntity,
        required: true,
    },
    transaction: {
        type: Object as () => TransactionEntity,
        required: true,
    },
    items: {
        type: Array as () => TransactionItemEntity[],
        required: true,
    },
});

const subTotal = props.items
    .reduce((total, item) => total + item.subtotal, 0)
    .toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });

const total = (
    props.items.reduce((total, item) => total + item.subtotal, 0) +
    props.transaction.shipping_cost
).toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
});
</script>

<template>
    <div
        class="w-full lg:w-[400px] outline outline-1 -outline-offset-1 outline-gray-300 rounded-2xl p-4 gap-y-4 flex flex-col gap-4"
    >
        <h3 class="text-lg font-semibold text-gray-700">Detail Pemesanan</h3>
        <div>
            <div class="flex flex-col gap-2">
                <OrderContentRow label="Invoice" :value="props.invoice.code" />

                <OrderContentRow
                    label="Tgl. Pemesanan"
                    :value="$formatDate(props.transaction.created_at)"
                />
                <OrderContentRow
                    label="Status"
                    :value="props.transaction.status"
                >
                    <template #value>
                        <!-- Status -->
                        <OrderStatusChip
                            :status="props.transaction.status"
                            :label="props.transaction.status?.toUpperCase()"
                        />
                    </template>
                </OrderContentRow>
                <OrderContentRow
                    label="Metode Pembayaran"
                    :value="props.transaction.payment_method.name"
                />
                <OrderContentRow
                    label="Metode Pengiriman"
                    :value="props.transaction.shipping_method.name"
                />

                <template
                    v-if="props.transaction.shipping_method.slug === 'courier'"
                >
                    <div class="my-2 border-b border-gray-300"></div>
                    <OrderContentRow
                        label="Provinsi"
                        :value="props.transaction.province_name"
                    />
                    <OrderContentRow
                        label="Kota"
                        :value="props.transaction.city_name"
                    />
                    <OrderContentRow
                        label="Alamat"
                        :value="props.transaction.address"
                    />
                </template>

                <template v-if="$slots.additionalInfo">
                    <div class="my-2 border-b border-gray-300"></div>
                    <slot name="additionalInfo" />
                </template>

                <!-- Divider -->
                <div class="my-2 border-b border-gray-300"></div>

                <OrderContentRow label="Sub Total" :value="subTotal" />
                <OrderContentRow
                    label="Biaya Pengiriman"
                    :value="
                        props.transaction.shipping_cost.toLocaleString(
                            'id-ID',
                            {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                            }
                        )
                    "
                />
                <div class="flex items-center justify-between">
                    <p class="text-lg font-bold text-gray-700">Total</p>
                    <p class="text-lg font-bold text-primary">
                        {{ total }}
                    </p>
                </div>

                <div v-if="$slots.actions" class="mt-1">
                    <slot name="actions" />
                </div>
            </div>
        </div>
    </div>
</template>
