<script setup lang="ts">
import { ref, computed, watch } from "vue";
import Chip from "@/Components/Chip.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { usePage, router } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/cart-store";
import { useOrderStore } from "@/stores/order-store";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import BaseDialog from "@/Components/BaseDialog.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";

const page = usePage();
const cartStore = useCartStore();
const orderStore = useOrderStore();

async function initScript() {
    const snapScript = "https://app.sandbox.midtrans.com/snap/snap.js";
    const clientKey = import.meta.env.VITE_MIDTRANS_CLIENT_KEY;

    const script = document.createElement("script");
    script.src = snapScript;
    script.setAttribute("data-client-key", clientKey);
    script.async = true;

    document.body.appendChild(script);

    return () => {
        document.body.removeChild(script);
    };
}
initScript();

const checkoutStatus = ref(null);

async function showSnap(invoice: InvoiceEntity) {
    const snapToken = invoice.snap_token;

    checkoutStatus.value = "loading";

    window.snap.pay(snapToken, {
        onSuccess: async function (result) {
            cartStore.clearSelectedItems();

            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });

            await axios
                .post(
                    `${page.props.ziggy.url}/api/confirm-payment`,
                    {
                        invoice_id: invoice.id,
                    },
                    {
                        headers: {
                            authorization: `Bearer ${localStorage.getItem(
                                "access_token"
                            )}`,
                        },
                    }
                )
                .then((response) => {
                    console.log("Payment confirmed", response.data);
                    router.visit(
                        route("order.success", {
                            invoice_code: invoice.code,
                        })
                    );
                })
                .catch((error) => {
                    console.error("Error confirming payment", error);
                });

            checkoutStatus.value = "success";
        },
        onPending: async function (result) {
            console.log("pending", result);
        },
        onError: async function (result) {
            console.log("error", result);
            openErrorDialog("Terjadi kesalahan saat memproses pembayaran");
            await cancelOrder(invoice.id);
            checkoutStatus.value = "error";
        },
        onClose: async function () {
            openErrorDialog(
                "Anda membatalkan pembayaran. Pesanan tidak akan diproses."
            );
            await cancelOrder(invoice.id);
            checkoutStatus.value = "error";
        },
    });
}

const cancelOrder = async (invoiceId: number) => {
    try {
        await axios.post(
            `${page.props.ziggy.url}/api/cancel-order`,
            { invoice_id: invoiceId },
            {
                headers: {
                    authorization: `Bearer ${localStorage.getItem(
                        "access_token"
                    )}`,
                },
            }
        );
    } catch (error) {
        console.error("Error cancelling order:", error);
        openErrorDialog("Gagal membatalkan pesanan");
    }
};

const showErrorDialog = ref(false);
const errorMessage = ref(null);

function openErrorDialog(message: string) {
    errorMessage.value = message;
    showErrorDialog.value = true;
}

function closeErrorDialog() {
    showErrorDialog.value = false;
    errorMessage.value = null;
}

const paymentMethods = page.props.paymentMethods as PaymentMethodEntity[];
const shippingMethods = page.props.shippingMethods as ShippingMethodEntity[];

const provinces = ref([] as ProvinceEntity[]);
const isProvinceDropdownOpen = ref(false);
const provinceSearch = ref("");
const filteredProvinces = computed(() => {
    return provinces.value.filter((province) =>
        province.province
            .toLowerCase()
            .includes(provinceSearch.value.toLowerCase())
    );
});

const cities = ref([] as CityEntity[]);
const isCityDropdownOpen = ref(false);
const citySearch = ref("");
const filteredCities = computed(() => {
    return cities.value.filter((city) =>
        city.city_name.toLowerCase().includes(citySearch.value.toLowerCase())
    );
});

function getProvinces() {
    axios
        .get(`${page.props.ziggy.url}/api/provinces`)
        .then((response) => {
            provinces.value = response.data.result;
        })
        .catch((error) => {});
}
getProvinces();

function getCities(provinceId: string) {
    axios
        .get(`${page.props.ziggy.url}/api/cities`, {
            params: { province_id: provinceId },
        })
        .then((response) => {
            cities.value = response.data.result;
        })
        .catch((error) => {});
}

function getShippingCost() {
    if (!form.city_id || !form.shipping_method) {
        form.shipping_cost = 0;
        return;
    }

    axios
        .get(`${page.props.ziggy.url}/api/shipping-cost`, {
            params: {
                destination: form.city_id,
            },
        })
        .then((response) => {
            form.shipping_cost = response.data.result.value || 0;
            form.estimated_delivery = response.data.result.etd || null;
        })
        .catch((error) => {
            form.shipping_cost = 0;
        });
}

const form = useForm({
    payment_method: orderStore.form.payment_method,
    shipping_method: orderStore.form.shipping_method,
    province_id: orderStore.form.province_id,
    city_id: orderStore.form.city_id,
    province: orderStore.form.province,
    city: orderStore.form.city,
    address: orderStore.form.address,
    shipping_cost: orderStore.form.shipping_cost || 0,
    estimated_delivery: null,
});

if (form.province_id) {
    getCities(form.province_id);
}

if (form.city_id) {
    getShippingCost();
}

const updateLocalForm = () => {
    orderStore.updateForm({
        payment_method: form.payment_method,
        shipping_method: form.shipping_method,
        province_id: form.province_id,
        province: form.province,
        city_id: form.city_id,
        city: form.city,
        address: form.address,
        shipping_cost: form.shipping_cost,
    } as OrderDetailFormModel);
};

watch(
    () => form.data(),
    (newForm) => {
        updateLocalForm();
    }
);

const total = computed(() => {
    if (form.shipping_method?.slug == "courier") {
        return cartStore.subTotal + (form.shipping_cost || 0);
    }
    return cartStore.subTotal;
});

const showAuthWarning = ref(false);

const submit = () => {
    if (!page.props.auth.user) {
        showAuthWarning.value = true;
        return;
    }

    checkoutStatus.value = "loading";

    axios
        .post(
            `${page.props.ziggy.url}/api/checkout`,
            {
                cart_items: cartStore.selectedItems.map((item) => ({
                    product_id: item.product_id,
                    variant_id: item.variant_id,
                    quantity: item.quantity,
                })),
                payment_method_id: form.payment_method?.id,
                shipping_method_id: form.shipping_method?.id,
                province_id: form.province_id,
                province_name: form.province?.province,
                city_id: form.city_id,
                city_name: form.city?.city_name,
                address: form.address,
            },
            {
                headers: {
                    authorization: `Bearer ${localStorage.getItem(
                        "access_token"
                    )}`,
                },
            }
        )
        .then((response) => {
            const invoice = response.data.result as InvoiceEntity;
            if (invoice.snap_token) {
                showSnap(invoice);
            } else {
                checkoutStatus.value = "success";
                cartStore.clearSelectedItems();
                router.visit(
                    route("order.success", { invoice_code: invoice.code })
                );
            }
        })
        .catch((error) => {
            checkoutStatus.value = "error";
            console.error("Checkout error:", error);
            if (error.response.status == 422) {
                form.errors = error.response.data.errors || {};
            } else {
                openErrorDialog("Terjadi kesalahan");
            }
        });
};
</script>

<template>
    <div
        class="w-full lg:w-[400px] outline outline-1 -outline-offset-1 outline-gray-300 rounded-2xl p-4 gap-y-4 flex flex-col gap-4"
    >
        <h3 class="text-lg font-semibold text-gray-700">Detail Pemesanan</h3>

        <div class="flex flex-col gap-y-3">
            <!-- Payment Method -->
            <div class="flex flex-col gap-y-2">
                <InputLabel
                    for="payment-method"
                    value="Metode Pembayaran"
                    class="!text-gray-500"
                />
                <div class="flex flex-wrap gap-2">
                    <Chip
                        v-for="payment in paymentMethods"
                        :key="payment.id"
                        :label="payment.name"
                        :selected="form.payment_method?.id == payment.id"
                        @click="form.payment_method = payment"
                    />
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="flex flex-col gap-y-2">
                <InputLabel
                    for="shipping-method"
                    value="Metode Pengiriman"
                    class="!text-gray-500"
                />
                <div class="flex flex-wrap gap-2">
                    <Chip
                        v-for="shipping in shippingMethods"
                        :key="shipping.id"
                        :label="shipping.name"
                        :selected="form.shipping_method?.id == shipping.id"
                        @click="form.shipping_method = shipping"
                    />
                </div>
            </div>

            <template v-if="form.shipping_method?.slug == 'courier'">
                <!-- Province -->
                <div class="flex flex-col gap-y-2">
                    <InputLabel
                        for="province"
                        value="Provinsi"
                        class="!text-gray-500"
                    />
                    <Dropdown
                        v-if="provinces"
                        id="province_id"
                        v-model="form.province_id"
                        :options="provinces"
                        option-label="name"
                        option-value="id"
                        placeholder="Pilih Provinsi"
                        align="left"
                        required
                        :error="form.errors.province_id"
                        @update:modelValue="form.errors.province_id = null"
                        @onOpen="isProvinceDropdownOpen = true"
                        @onClose="isProvinceDropdownOpen = false"
                    >
                        <template #trigger>
                            <TextInput
                                :modelValue="
                                    form.province_id && !isProvinceDropdownOpen
                                        ? form.province?.province
                                        : provinceSearch
                                "
                                @update:modelValue="
                                    form.province_id && !isProvinceDropdownOpen
                                        ? null
                                        : (provinceSearch = $event)
                                "
                                class="w-full"
                                placeholder="Pilih Provinsi"
                                :error="
                                    form.errors?.province_id
                                        ? form.errors?.province_id[0] || null
                                        : null
                                "
                            >
                                <template #suffix>
                                    <button
                                        v-if="
                                            form.province_id &&
                                            !isProvinceDropdownOpen
                                        "
                                        type="button"
                                        class="absolute p-[7px] text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100 transition-all duration-300 ease-in-out"
                                        @click="
                                            form.province_id = null;
                                            provinceSearch = '';
                                            form.city_id = null;
                                            form.city = null;
                                            cities = [];
                                            form.shipping_cost = 0;
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            class="size-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="absolute p-2 top-1.5 right-1"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            class="size-4 fill-gray-400"
                                        >
                                            <path
                                                d="M18.6054 7.3997C18.4811 7.273 18.3335 7.17248 18.1709 7.10389C18.0084 7.0353 17.8342 7 17.6583 7C17.4823 7 17.3081 7.0353 17.1456 7.10389C16.9831 7.17248 16.8355 7.273 16.7112 7.3997L11.4988 12.7028L6.28648 7.3997C6.03529 7.14415 5.69462 7.00058 5.33939 7.00058C4.98416 7.00058 4.64348 7.14415 4.3923 7.3997C4.14111 7.65526 4 8.00186 4 8.36327C4 8.72468 4.14111 9.07129 4.3923 9.32684L10.5585 15.6003C10.6827 15.727 10.8304 15.8275 10.9929 15.8961C11.1554 15.9647 11.3296 16 11.5055 16C11.6815 16 11.8557 15.9647 12.0182 15.8961C12.1807 15.8275 12.3284 15.727 12.4526 15.6003L18.6188 9.32684C19.1293 8.80747 19.1293 7.93274 18.6054 7.3997Z"
                                            />
                                        </svg>
                                    </button>
                                </template>
                            </TextInput>
                        </template>
                        <template #content>
                            <ul class="overflow-y-auto max-h-60">
                                <li
                                    v-for="province in filteredProvinces"
                                    :key="province.province_id"
                                    @click="
                                        isProvinceDropdownOpen = false;

                                        form.province_id = province.province_id;
                                        form.province = province;
                                        provinceSearch = '';

                                        form.city_id = null;
                                        form.city = null;
                                        cities = [];

                                        getCities(province.province_id);
                                    "
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                >
                                    {{ province.province }}
                                </li>
                            </ul>
                        </template>
                    </Dropdown>
                </div>

                <!-- City -->
                <div class="flex flex-col gap-y-2">
                    <InputLabel
                        for="city"
                        value="Kota/Kabupaten"
                        class="!text-gray-500"
                    />
                    <Dropdown
                        v-if="cities"
                        id="city_id"
                        v-model="form.city_id"
                        :options="cities"
                        option-label="name"
                        option-value="id"
                        placeholder="Pilih Kota/Kabupaten"
                        align="left"
                        required
                        :error="form.errors.city_id"
                        @update:modelValue="form.errors.city_id = null"
                        @onOpen="isCityDropdownOpen = true"
                        @onClose="isCityDropdownOpen = false"
                    >
                        <template #trigger>
                            <TextInput
                                :modelValue="
                                    form.city_id && !isCityDropdownOpen
                                        ? `${form.city?.type} ${form.city?.city_name}`
                                        : citySearch
                                "
                                @update:modelValue="
                                    form.city_id && !isCityDropdownOpen
                                        ? null
                                        : (citySearch = $event)
                                "
                                class="w-full"
                                placeholder="Pilih Kota/Kabupaten"
                                :error="
                                    form.errors?.city_id
                                        ? form.errors?.city_id[0] || null
                                        : null
                                "
                            >
                                <template #suffix>
                                    <button
                                        v-if="
                                            form.city_id && !isCityDropdownOpen
                                        "
                                        type="button"
                                        class="absolute p-[7px] text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100 transition-all duration-300 ease-in-out"
                                        @click="
                                            form.city_id = null;
                                            citySearch = '';
                                            form.shipping_cost = 0;
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            class="size-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="absolute p-2 top-1.5 right-1"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            class="size-4 fill-gray-400"
                                        >
                                            <path
                                                d="M18.6054 7.3997C18.4811 7.273 18.3335 7.17248 18.1709 7.10389C18.0084 7.0353 17.8342 7 17.6583 7C17.4823 7 17.3081 7.0353 17.1456 7.10389C16.9831 7.17248 16.8355 7.273 16.7112 7.3997L11.4988 12.7028L6.28648 7.3997C6.03529 7.14415 5.69462 7.00058 5.33939 7.00058C4.98416 7.00058 4.64348 7.14415 4.3923 7.3997C4.14111 7.65526 4 8.00186 4 8.36327C4 8.72468 4.14111 9.07129 4.3923 9.32684L10.5585 15.6003C10.6827 15.727 10.8304 15.8275 10.9929 15.8961C11.1554 15.9647 11.3296 16 11.5055 16C11.6815 16 11.8557 15.9647 12.0182 15.8961C12.1807 15.8275 12.3284 15.727 12.4526 15.6003L18.6188 9.32684C19.1293 8.80747 19.1293 7.93274 18.6054 7.3997Z"
                                            />
                                        </svg>
                                    </button>
                                </template>
                            </TextInput>
                        </template>
                        <template #content>
                            <ul class="overflow-y-auto max-h-60">
                                <li
                                    v-for="city in filteredCities"
                                    :key="city.city_id"
                                    @click="
                                        isCityDropdownOpen = false;
                                        form.city_id = city.city_id;
                                        form.city = city;
                                        citySearch = '';
                                        getShippingCost();
                                    "
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                >
                                    {{ city.type }} {{ city.city_name }}
                                </li>
                            </ul>
                        </template>
                    </Dropdown>
                </div>

                <!-- Address -->
                <div class="flex flex-col gap-y-2">
                    <InputLabel
                        for="address"
                        value="Alamat Lengkap"
                        class="!text-gray-500"
                    />
                    <TextAreaInput
                        id="address"
                        v-model="form.address"
                        class="w-full"
                        placeholder="Masukkan alamat lengkap"
                        @update:modelValue="form.errors.address = null"
                        :error="
                            form.errors?.address
                                ? form.errors?.address[0] || null
                                : null
                        "
                    />
                    <p
                        v-if="form.estimated_delivery"
                        class="text-sm text-gray-500"
                    >
                        *Estimasi {{ form.estimated_delivery }} hari kerja
                    </p>
                </div>
            </template>

            <!-- Divider -->
            <div class="my-2 border-b border-gray-300"></div>

            <!-- Summary -->
            <div class="flex flex-col gap-y-2">
                <!-- Sub Total -->
                <div class="flex items-center justify-between">
                    <p class="text-gray-700">Sub Total</p>
                    <p class="font-semibold text-gray-700">
                        {{
                            cartStore.subTotal.toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            })
                        }}
                    </p>
                </div>

                <!-- Shipping Cost -->
                <div class="flex items-center justify-between">
                    <p class="text-gray-700">Biaya Pengiriman</p>
                    <p class="text-gray-700">
                        {{
                            (cartStore.selectedItems.length > 0 &&
                            form.shipping_method?.slug == "courier"
                                ? form.shipping_cost
                                : 0
                            ).toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            })
                        }}
                    </p>
                </div>

                <!-- Total -->
                <div class="flex items-center justify-between">
                    <p class="text-lg font-bold text-gray-700">Total</p>
                    <p class="text-lg font-bold text-primary">
                        {{
                            (cartStore.selectedItems.length > 0
                                ? total
                                : 0
                            ).toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            })
                        }}
                    </p>
                </div>

                <PrimaryButton
                    class="w-full py-3 mt-2"
                    :disabled="
                        !form.payment_method ||
                        !form.shipping_method ||
                        cartStore.selectedItems.length == 0 ||
                        checkoutStatus === 'loading'
                    "
                    @click="submit"
                >
                    Pesan Sekarang
                </PrimaryButton>
            </div>

            <!-- Divider -->
            <div class="my-2 border-b border-gray-300"></div>

            <!-- Note -->
            <p class="text-sm text-gray-500">Catatan:</p>
            <ul
                class="flex flex-col pl-4 text-sm text-gray-500 list-disc list-outside gap-y-2"
            >
                <li>
                    Pastikan alamat dan informasi pemesanan sudah benar sebelum
                    melanjutkan ke pembayaran.
                </li>
                <li>Total harga belum termasuk biaya layanan transfer.</li>
            </ul>
        </div>

        <BaseDialog
            :show="showAuthWarning"
            title="Masuk untuk Melanjutkan"
            description="Anda harus masuk untuk melanjutkan pemesanan. Silakan masuk atau daftar akun terlebih dahulu."
            positiveButtonText="Masuk"
            negativeButtonText="Daftar"
            @close="showAuthWarning = false"
            @positiveClicked="
                showAuthWarning = false;
                $inertia.visit(
                    route('login', {
                        redirect: route('my-cart'),
                    })
                );
            "
            @negativeClicked="
                showAuthWarning = false;
                $inertia.visit(
                    route('register', {
                        redirect: route('my-cart'),
                    })
                );
            "
        />

        <ErrorDialog
            v-if="showErrorDialog"
            :title="errorMessage"
            :show="showErrorDialog"
            @close="closeErrorDialog"
        />
    </div>
</template>
