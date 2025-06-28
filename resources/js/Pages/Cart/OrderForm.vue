<script setup lang="ts">
import { ref, computed, watch } from "vue";
import Chip from "@/Components/Chip.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/cart-store";
import { useOrderStore } from "@/stores/order-store";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import BaseDialog from "@/Components/BaseDialog.vue";

const page = usePage();
const cartStore = useCartStore();
const orderStore = useOrderStore();

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

watch(
    () => form.payment_method,
    (newPaymentMethod) => {
        orderStore.updateForm({
            ...orderStore.form,
            payment_method: newPaymentMethod,
        } as OrderDetailFormModel);
    }
);

watch(
    () => form.shipping_method,
    (newShippingMethod) => {
        orderStore.updateForm({
            ...orderStore.form,
            shipping_method: newShippingMethod,
        } as OrderDetailFormModel);
    }
);

watch(
    () => form.province_id,
    (newProvinceId) => {
        orderStore.updateForm({
            ...orderStore.form,
            province_id: newProvinceId,
            province: form.province,
        } as OrderDetailFormModel);
    }
);

watch(
    () => form.city_id,
    (newCityId) => {
        orderStore.updateForm({
            ...orderStore.form,
            city_id: newCityId,
            city: form.city,
        } as OrderDetailFormModel);
    }
);

watch(
    () => form.address,
    (newAddress) => {
        orderStore.updateForm({
            ...orderStore.form,
            address: newAddress,
        } as OrderDetailFormModel);
    }
);

watch(
    () => form.shipping_cost,
    (newShippingCost) => {
        orderStore.updateForm({
            ...orderStore.form,
            shipping_cost: newShippingCost,
        } as OrderDetailFormModel);
    }
);

const total = computed(() => {
    return cartStore.subTotal + (form.shipping_cost || 0);
});

const showAuthWarning = ref(false);

const submit = () => {
    if (!page.props.auth.user) {
        showAuthWarning.value = true;
        return;
    }
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
                        :selected="form.payment_method.id == payment.id"
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
                        :selected="form.shipping_method.id == shipping.id"
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
                        :error="form.errors.address"
                        @update:modelValue="form.errors.address = null"
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
                    <p class="text-gray-700">
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
                    :disabled="!form.payment_method || !form.shipping_method"
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
                        redirect: route('cart'),
                    })
                );
            "
            @negativeClicked="
                showAuthWarning = false;
                $inertia.visit(
                    route('register', {
                        redirect: route('cart'),
                    })
                );
            "
        />
    </div>
</template>
