<script setup lang="ts">
import LandingLayout from "@/Layouts/LandingLayout.vue";
import OrderDetail from "./Order/OrderDetail.vue";

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
</script>

<template>
    <LandingLayout title="Berhasil Membuat Pesanan">
        <div
            class="p-6 sm:p-12 md:px-[100px] md:py-[60px] flex flex-col gap-2 sm:gap-3"
        >
            <h1
                class="text-2xl font-bold text-start sm:text-center sm:text-3xl"
            >
                Pesanan Berhasil!
            </h1>

            <p
                class="text-sm text-gray-700 text-start sm:text-center sm:text-base"
            >
                Terima kasih telah melakukan pemesanan. Pesanan Anda telah
                berhasil dibuat.
            </p>
        </div>

        <OrderDetail
            data-aos="fade-up"
            data-aos-duration="600"
            :invoice="props.invoice"
            :transaction="props.transaction"
            :items="props.items"
        />
    </LandingLayout>
</template>
