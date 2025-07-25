<script setup lang="ts">
import LandingSection from "@/Components/LandingSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import CartItem from "./Cart/CartItem.vue";
import { Link } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/cart-store";
import OrderForm from "./Cart/OrderForm.vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import QuantityInput from "@/Components/QuantityInput.vue";
import DeleteConfirmationDialog from "@/Components/DeleteConfirmationDialog.vue";
import JoinUs from "@/Components/JoinUs.vue";
import { router } from "@inertiajs/vue3";

const page = usePage();
if (page.props.flash.access_token) {
    localStorage.setItem("access_token", page.props.flash.access_token);
}

if (route().params.order_id) {
    router.visit(route("order.success", { order_id: route().params.order_id }));
}

const cartStore = useCartStore();

if (cartStore.items.length > 0) {
    const syncCart = JSON.parse(
        JSON.stringify(cartStore.items, (key, value) => {
            // Remove the 'variant' property from each item
            if (key === "variant") {
                return undefined;
            }
            return value;
        })
    );

    axios
        .post(`${page.props.ziggy.url}/api/sync-cart`, {
            cart_items: syncCart,
        })
        .then((response) => {
            cartStore.updateAllItems(response.data.result);
        });
}

function formatPrice(price = 0) {
    return price.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });
}
</script>

<template>
    <LandingLayout :title="`Keranjang Saya (${cartStore.items.length})`">
        <div
            class="p-6 sm:p-12 md:px-[100px] md:py-[60px] flex flex-col gap-2 sm:gap-3"
            :class="{
                'min-h-[60vh] items-center justify-center gap-4':
                    cartStore.items.length == 0,
            }"
        >
            <h1
                class="text-2xl font-bold text-start sm:text-center sm:text-3xl"
            >
                {{
                    cartStore.items.length > 0
                        ? `Keranjang Saya (${cartStore.items.length} item)`
                        : "Keranjang Kosong"
                }}
            </h1>
            <div
                v-if="cartStore.items.length == 0"
                class="flex flex-col items-center gap-y-6"
            >
                <p class="text-sm text-center text-gray-700 sm:text-base">
                    Anda belum menambahkan produk ke keranjang.
                </p>
                <Link :href="route('catalog')">
                    <PrimaryButton class="py-2.5 px-5 mx-auto">
                        Mulai Belanja
                    </PrimaryButton>
                </Link>
            </div>
            <p
                class="text-sm text-gray-700 text-start sm:text-center sm:text-base"
                v-else
            >
                Periksa kembali sebelum buat pesanan.
            </p>
        </div>

        <div
            data-aos="fade-up"
            data-aos-duration="600"
            class="p-6 !pt-0 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20"
        >
            <LandingSection
                v-if="cartStore.items.length > 0"
                class="!items-start !justify-start"
            >
                <div
                    class="flex flex-col items-center justify-center w-full gap-5 mx-auto lg:flex-row lg:items-start sm:gap-12 max-w-7xl"
                >
                    <!-- Cart Items -->
                    <div>
                        <div
                            v-for="(item, index) in cartStore.items"
                            :key="index"
                        >
                            <CartItem
                                :item="item"
                                :showDivider="
                                    index !== cartStore.items.length - 1
                                "
                                @toggleItem="cartStore.toggleItem(item)"
                                @subtract="
                                    cartStore.updateItem({
                                        ...item,
                                        quantity: item.quantity - 1,
                                    })
                                "
                                @updateQuantity="
                                    cartStore.updateItem({
                                        ...item,
                                        quantity: parseInt($event),
                                    })
                                "
                                @add="
                                    cartStore.updateItem({
                                        ...item,
                                        quantity: item.quantity + 1,
                                    })
                                "
                                @remove="item.showDeleteConfirmation = true"
                            >
                                <template #actions>
                                    <div
                                        class="flex items-center justify-between w-full gap-4 xl:flex-col xl:items-end xl:justify-normal xl:w-fit"
                                    >
                                        <button
                                            class="text-sm text-gray-500 sm:block sm:text-base hover:text-red-500 sm:w-fit text-start"
                                            @click="
                                                item.showDeleteConfirmation = true
                                            "
                                        >
                                            Hapus
                                        </button>

                                        <div
                                            class="flex items-center justify-end w-full gap-y-2 gap-x-4 xl:flex-col xl:items-end xl:w-fit"
                                        >
                                            <QuantityInput
                                                :modelValue="item.quantity"
                                                :unit="item.variant.unit"
                                                :max="
                                                    item.variant
                                                        .current_stock_level
                                                "
                                                :showAvailability="false"
                                                :label="null"
                                                @update:modelValue="
                                                    cartStore.updateItem({
                                                        ...item,
                                                        quantity: $event,
                                                    })
                                                "
                                            />

                                            <p
                                                class="text-base font-semibold text-gray-800 text-end w-[110px]"
                                            >
                                                {{
                                                    formatPrice(
                                                        item.variant
                                                            .final_selling_price *
                                                            item.quantity
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </CartItem>

                            <DeleteConfirmationDialog
                                :show="item.showDeleteConfirmation"
                                :item="item"
                                title="Hapus produk ini dari keranjang?"
                                :description="item.variant.name"
                                positiveButtonText="Hapus"
                                @delete="
                                    cartStore.removeItem(item);
                                    item.showDeleteConfirmation = false;
                                "
                                @close="item.showDeleteConfirmation = false"
                            />
                        </div>
                    </div>

                    <!-- Detail Order -->
                    <OrderForm />
                </div>
            </LandingSection>

            <!-- Join Us -->
            <LandingSection id="join">
                <JoinUs />
            </LandingSection>
        </div>
    </LandingLayout>
</template>
