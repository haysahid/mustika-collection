<script setup>
import LandingSection from "@/Components/LandingSection.vue";
import OrderItem from "./OrderItem.vue";
import OrderContentRow from "@/Components/OrderContentRow.vue";

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
    transaction: {
        type: Object,
        required: true,
    },
    items: {
        type: Array,
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

const histories = [
    {
        date: "2023-10-01 12:00:00",
        title: "Pesanan Dibuat",
        done: true,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M15 4H6V20H18V7H15V4ZM6 2H16L20 6V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V4C4 3.46957 4.21071 2.96086 4.58579 2.58579C4.96086 2.21071 5.46957 2 6 2ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z" />
            </svg>`,
    },
    {
        date: "2023-10-02 14:30:00",
        title: "Pembayaran Diterima",
        done: true,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M20 8H4V6H20M20 18H4V12H20M20 4H4C2.89 4 2 4.89 2 6V18C2 18.5304 2.21071 19.0391 2.58579 19.4142C2.96086 19.7893 3.46957 20 4 20H20C20.5304 20 21.0391 19.7893 21.4142 19.4142C21.7893 19.0391 22 18.5304 22 18V6C22 5.46957 21.7893 4.96086 21.4142 4.58579C21.0391 4.21071 20.5304 4 20 4Z"/>
            </svg>`,
    },
    {
        date: "2023-10-03 16:45:00",
        title: "Pesanan Dikirim",
        done: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M18 18.5C18.83 18.5 19.5 17.83 19.5 17C19.5 16.17 18.83 15.5 18 15.5C17.17 15.5 16.5 16.17 16.5 17C16.5 17.83 17.17 18.5 18 18.5ZM19.5 9.5H17V12H21.46L19.5 9.5ZM6 18.5C6.83 18.5 7.5 17.83 7.5 17C7.5 16.17 6.83 15.5 6 15.5C5.17 15.5 4.5 16.17 4.5 17C4.5 17.83 5.17 18.5 6 18.5ZM20 8L23 12V17H21C21 18.66 19.66 20 18 20C16.34 20 15 18.66 15 17H9C9 18.66 7.66 20 6 20C4.34 20 3 18.66 3 17H1V6C1 4.89 1.89 4 3 4H17V8H20ZM3 6V15H3.76C4.31 14.39 5.11 14 6 14C6.89 14 7.69 14.39 8.24 15H15V6H3ZM10 7L13.5 10.5L10 14V11.5H5V9.5H10V7Z"/>
            </svg>`,
    },
    {
        date: "2023-10-04 10:15:00",
        title: "Pesanan Diterima",
        done: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17ZM6 2C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 21.11 4.89 22 6 22H13.81C13.45 21.38 13.2 20.7 13.08 20H6V4H13V9H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M8 12V14H16V12M8 16V18H13V16H8Z"/>
            </svg>`,
    },
];
</script>

<template>
    <div
        data-aos="fade-up"
        data-aos-duration="600"
        class="p-6 !pt-0 sm:p-12 md:p-[100px] flex flex-col gap-4 sm:gap-12"
    >
        <!-- Tracking -->
        <div class="flex flex-col items-center gap-4 mx-auto w-fit sm:gap-6">
            <div
                class="flex items-start justify-center gap-4 md:gap-8 lg:gap-12"
            >
                <div
                    v-for="(history, index) in histories"
                    :key="history.date"
                    class="flex flex-col items-center gap-2.5 sm:gap-4"
                >
                    <div
                        v-if="history.icon"
                        v-html="history.icon"
                        class="[&>svg]:size-6 sm:[&>svg]:size-8"
                        :class="{
                            'fill-primary': history.done,
                            'fill-gray-400': !history.done,
                        }"
                    ></div>

                    <div class="flex flex-col items-center text-center">
                        <p
                            class="text-xs text-gray-600 sm:text-sm"
                            :class="{ 'text-primary': history.done }"
                        >
                            {{ history.date }}
                        </p>
                        <p
                            class="text-sm font-semibold text-gray-800 sm:text-base"
                            :class="{ 'text-primary': history.done }"
                        >
                            {{ history.title }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Line -->
            <div class="relative w-full h-1.5 bg-gray-200 rounded-full">
                <div
                    class="absolute top-0 left-0 h-full rounded-full bg-primary"
                    :style="{
                        width: `${
                            (histories.filter((h) => h.done).length /
                                histories.length) *
                            100
                        }%`,
                    }"
                ></div>
            </div>
        </div>

        <!-- Details -->
        <LandingSection class="!flex-col !justify-start !min-h-[56vh]">
            <div
                class="flex flex-col items-center justify-center w-full gap-2 mx-auto lg:flex-row lg:items-start sm:gap-12 max-w-7xl"
            >
                <!-- Items -->
                <div v-for="(item, index) in props.items" :key="item.id">
                    <OrderItem
                        :item="item"
                        :showDivider="index !== props.items.length - 1"
                    />
                </div>

                <!-- Summary -->
                <div
                    class="w-full lg:w-[400px] outline outline-1 -outline-offset-1 outline-gray-300 rounded-2xl p-4 gap-y-4 flex flex-col gap-4"
                >
                    <h3 class="text-lg font-semibold text-gray-700">
                        Detail Pemesanan
                    </h3>
                    <div>
                        <div class="flex flex-col gap-2">
                            <OrderContentRow
                                label="Invoice"
                                :value="props.invoice.code"
                            />

                            <OrderContentRow
                                label="Tgl. Pemesanan"
                                :value="
                                    $formatDate(props.transaction.created_at)
                                "
                            />
                            <OrderContentRow
                                label="Status"
                                :value="props.transaction.status"
                            />
                            <OrderContentRow
                                label="Metode Pembayaran"
                                :value="props.transaction.payment_method.name"
                            />
                            <OrderContentRow
                                label="Metode Pengiriman"
                                :value="props.transaction.shipping_method.name"
                            />

                            <!-- Divider -->
                            <div class="my-2 border-b border-gray-300"></div>

                            <OrderContentRow
                                label="Sub Total"
                                :value="subTotal"
                            />
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
                                <p class="text-lg font-bold text-gray-700">
                                    Total
                                </p>
                                <p class="text-lg font-bold text-primary">
                                    {{ total }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </LandingSection>
    </div>
</template>
