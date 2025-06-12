<script setup>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ImageInput from "@/Components/ImageInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import Checkbox from "@/Components/Checkbox.vue";

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    ...props.product,
    categories: props.product?.categories || [],
    sizes: props.product?.sizes || [],
    images:
        props.product?.images?.map((image) => ({
            image: "/storage/" + image.image,
        })) || [],
});

const page = usePage();

const brands = page.props.brands || [];
const brandSearch = ref("");
const isBrandDropdownOpen = ref(false);

const filteredBrands = computed(() => {
    return brands.filter((brand) =>
        brand.name.toLowerCase().includes(brandSearch.value.toLowerCase())
    );
});

const categories = page.props.categories || [];
const sizes = page.props.sizes || [];
const colors = page.props.colors || [];

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("admin.login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <form action="" class="max-w-3xl">
        <div class="flex flex-col items-start gap-4">
            <h2 class="text-lg font-bold">Informasi Produk</h2>

            <!-- Name -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="name"
                    value="Nama Produk"
                    class="w-[100px] sm:w-1/5 text-lg font-bold"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Masukkan Nama Produk"
                    class="block w-full mt-1"
                    required
                    :autofocus="true"
                    :error="form.errors.username"
                    @update:modelValue="form.errors.username = null"
                />
            </div>

            <!-- Brand -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="brand_id"
                    value="Nama Brand"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <Dropdown
                    id="brand_id"
                    v-model="form.brand_id"
                    :options="form.brands"
                    option-label="name"
                    option-value="id"
                    placeholder="Pilih Brand"
                    align="left"
                    class="block w-full mt-1"
                    required
                    :error="form.errors.brand_id"
                    @update:modelValue="form.errors.brand_id = null"
                    @onOpen="isBrandDropdownOpen = true"
                    @onClose="isBrandDropdownOpen = false"
                >
                    <template #trigger>
                        <TextInput
                            :modelValue="
                                form.brand_id && !isBrandDropdownOpen
                                    ? form.brand.name
                                    : brandSearch
                            "
                            @update:modelValue="
                                form.brand_id && !isBrandDropdownOpen
                                    ? null
                                    : (brandSearch = $event)
                            "
                            class="w-full"
                            placeholder="Pilih Brand"
                        >
                            <template #suffix>
                                <button
                                    v-if="form.brand_id && !isBrandDropdownOpen"
                                    type="button"
                                    class="absolute p-[7px] text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100"
                                    @click="
                                        form.brand_id = null;
                                        form.brand = null;
                                        brandSearch = '';
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="size-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
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
                                v-for="brand in filteredBrands"
                                :key="brand.id"
                                @click="
                                    form.brand_id = brand.id;
                                    form.brand = brand;
                                "
                                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                            >
                                {{ brand.name }}
                            </li>
                        </ul>
                    </template>
                </Dropdown>
            </div>

            <!-- Image -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="image"
                    value="Gambar Produk"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <div
                    class="grid w-full grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                >
                    <ImageInput
                        v-for="(image, index) in form.images"
                        :key="index"
                        :id="`image-${index}`"
                        v-model="form.images[index].image"
                        type="file"
                        accept="image/*"
                        placeholder="Upload Produk"
                        class="block w-full mt-1 h-"
                        width="max-w-[180px]"
                        height="h-[120px]"
                        :error="form.errors.images?.[index]"
                        @update:modelValue="
                            form.errors.images[index] = null;
                            if (!image.image) {
                                form.images.splice(index, 1);
                            }
                        "
                    />
                </div>
            </div>

            <!-- Selling Price -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="selling_price"
                    value="Harga "
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="selling_price"
                    v-model.number="form.selling_price"
                    type="number"
                    placeholder="Masukkan Harga"
                    class="block w-full mt-1"
                    required
                    autocomplete="selling_price"
                    :error="form.errors.selling_price"
                    @update:modelValue="form.errors.selling_price = null"
                />
            </div>

            <!-- Discount -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="discount"
                    value="Diskon (%)"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="discount"
                    v-model.number="form.discount"
                    type="number"
                    placeholder="Masukkan Diskon"
                    class="block w-full mt-1"
                    required
                    autocomplete="discount"
                    :error="form.errors.discount"
                    @update:modelValue="form.errors.discount = null"
                />
            </div>

            <!-- Stock -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="stock"
                    value="Stok"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="stock"
                    v-model.number="form.stock"
                    type="number"
                    placeholder="Masukkan Stok"
                    class="block w-full mt-1"
                    required
                    autocomplete="stock"
                    :error="form.errors.stock"
                    @update:modelValue="form.errors.stock = null"
                />
            </div>

            <!-- Categories -->
            <div
                class="flex flex-col w-full gap-y-1.5 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="categories"
                    value="Kategori Produk"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <div class="flex flex-col items-start w-full gap-3 mt-[1px]">
                    <div
                        class="grid w-full grid-cols-1 gap-2 sm:gap-2.5 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="category in categories || []"
                            :key="category.id"
                            class="flex items-center justify-start"
                        >
                            <label
                                :for="`category-${category.id}`"
                                class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer justify-start"
                            >
                                <Checkbox
                                    :id="`category-${category.id}`"
                                    :value="category.id.toString()"
                                    :label="category.name"
                                    :checked="
                                        form.categories
                                            .map((c) => c.id)
                                            .includes(category.id)
                                    "
                                    @update:checked="
                                        form.categories
                                            .map((c) => c.id)
                                            .includes(category.id)
                                            ? (form.categories =
                                                  form.categories.filter(
                                                      (c) =>
                                                          c.id !== category.id
                                                  ))
                                            : form.categories.push(category)
                                    "
                                />
                                <InputLabel
                                    :for="`category-${category.id}`"
                                    :value="category.name"
                                    class="text-sm text-gray-500"
                                />
                            </label>
                        </div>
                    </div>
                    <PrimaryButton
                        type="button"
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-100 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-100"
                        @click="
                            $inertia.visit(route('admin.certificate.create'))
                        "
                    >
                        + Tambah Kategori Produk
                    </PrimaryButton>
                </div>
            </div>

            <h2 class="mt-4 text-lg font-bold">Rincian Produk</h2>

            <!-- Material -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="material"
                    value="Jenis Bahan"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="material"
                    v-model="form.material"
                    type="text"
                    placeholder="Masukkan Nama Jenis Bahan"
                    class="block w-full mt-1"
                    required
                    autocomplete="material"
                    :error="form.errors.material"
                    @update:modelValue="form.errors.material = null"
                />
            </div>

            <!-- Sizes -->
            <div
                class="flex flex-col w-full gap-y-1.5 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="sizes"
                    value="Ukuran Produk"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <div class="flex flex-col items-start w-full gap-3 mt-[1px]">
                    <div class="grid w-full max-w-sm grid-cols-4 gap-3">
                        <div
                            v-for="size in sizes || []"
                            :key="size.id"
                            class="flex items-center justify-start"
                        >
                            <label
                                :for="`size-${size.id}`"
                                class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer justify-start"
                            >
                                <Checkbox
                                    :id="`size-${size.id}`"
                                    :value="size.id.toString()"
                                    :label="size.name"
                                    :checked="
                                        form.sizes
                                            .map((s) => s.id)
                                            .includes(size.id)
                                    "
                                    @update:checked="
                                        form.sizes
                                            .map((s) => s.id)
                                            .includes(size.id)
                                            ? (form.sizes = form.sizes.filter(
                                                  (s) => s.id !== size.id
                                              ))
                                            : form.sizes.push(size)
                                    "
                                />
                                <InputLabel
                                    :for="`size-${size.id}`"
                                    :value="size.name"
                                    class="text-sm text-gray-500"
                                />
                            </label>
                        </div>
                    </div>
                    <PrimaryButton
                        type="button"
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-100 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-100"
                        @click="$inertia.visit(route('admin.size.create'))"
                    >
                        + Tambah Ukuran Produk
                    </PrimaryButton>
                </div>
            </div>

            <!-- Colors -->
            <div
                class="flex flex-col w-full gap-y-1.5 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="colors"
                    value="Warna Produk"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <div class="flex flex-col items-start w-full gap-3 mt-[1px]">
                    <div
                        class="grid w-full grid-cols-2 gap-2 sm:gap-2.5 md:grid-cols-3 lg:grid-cols-4"
                    >
                        <div
                            v-for="color in colors || []"
                            :key="color.id"
                            class="flex items-center justify-start"
                        >
                            <label
                                :for="`color-${color.id}`"
                                class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer justify-start"
                            >
                                <Checkbox
                                    :id="`color-${color.id}`"
                                    :value="color.id.toString()"
                                    :label="color.name"
                                    :checked="form.color?.id == color.id"
                                    @update:checked="
                                        if (form.color_id == color.id) {
                                            form.color_id = null;
                                            form.color = null;
                                        } else {
                                            form.color_id = color.id;
                                            form.color = color;
                                        }
                                    "
                                />
                                <InputLabel
                                    :for="`color-${color.id}`"
                                    :value="color.name"
                                    class="text-sm text-gray-500"
                                />
                            </label>
                        </div>
                    </div>
                    <PrimaryButton
                        type="button"
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-100 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-100"
                        @click="$inertia.visit(route('admin.color.create'))"
                    >
                        + Tambah Warna Lain
                    </PrimaryButton>
                </div>
            </div>

            <!-- Description -->
            <div class="flex flex-col items-start w-full gap-1 sm:gap-1.5">
                <InputLabel
                    for="description"
                    value="Deskripsi Produk"
                    class="text-lg font-bold"
                />
                <TextAreaInput
                    id="description"
                    v-model="form.description"
                    type="text"
                    placeholder="Masukkan Deskripsi"
                    class="block w-full mt-1"
                    required
                    autocomplete="description"
                    :error="form.errors.description"
                    @update:modelValue="form.errors.description = null"
                />
            </div>

            <PrimaryButton class="mt-4" @click="submit">
                Simpan Data
            </PrimaryButton>
        </div>
    </form>
</template>
