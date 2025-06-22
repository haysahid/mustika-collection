<script setup>
import { ref, computed, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ImageInput from "@/Components/ImageInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import Checkbox from "@/Components/Checkbox.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";
import { useDraggable, VueDraggable } from "vue-draggable-plus";
import ProductLinkForm from "./ProductLinkForm.vue";
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
});

const form = useForm(
    props.product
        ? {
              ...props.product,
              categories: props.product?.categories || [],
              sizes: props.product?.sizes || [],
              images: [
                  ...(props.product?.images?.map((image) => ({
                      ...image,
                      image: "/storage/" + image.image,
                  })) || []),
                  { id: "new-1", image: null },
              ],
              links: [
                  ...(props.product?.links?.map(function (link) {
                      if (!link.platform) return link;
                      return {
                          ...link,
                          platform: {
                              ...link.platform,
                              icon: "/storage/" + link.platform.icon,
                          },
                      };
                  }) || []),
              ],
          }
        : {
              name: null,
              brand_id: null,
              brand: null,

              selling_price: null,
              discount: 0,
              stock: 0,
              color_id: null,
              color: null,
              material: null,
              description: null,
              categories: [],
              sizes: [],
              images: [{ id: "new-1", image: null }],
              links: [],
          }
);

const drag = ref(false);

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

function uploadNewImage(image, index) {
    const token = `Bearer ${localStorage.getItem("access_token")}`;

    const formData = new FormData();
    formData.append("product_id", props.product.id);
    formData.append("image", image.image);
    formData.append("order", index);

    axios
        .post(`${page.props.ziggy.url}/api/admin/product-image`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: token,
            },
        })
        .then((response) => {
            form.images[index] = {
                ...response.data.result,
                image: "/storage/" + response.data.result.image,
            };
        })
        .catch((error) => {
            if (error.response?.data?.error) {
                openErrorDialog(error.response.data.error);
            }
        });
}

function updateImage(index, image) {
    const token = `Bearer ${localStorage.getItem("access_token")}`;

    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("order", index);

    axios
        .post(
            `${page.props.ziggy.url}/api/admin/product-image/${image.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: token,
                },
            }
        )
        .then((response) => {
            form.images[index] = {
                ...response.data.result,
                image: "/storage/" + response.data.result.image,
            };
        })
        .catch((error) => {
            if (error.response?.data?.error) {
                openErrorDialog(error.response.data.error);
            }
        });
}

function updateImages() {
    const images = form.images || [];

    images.forEach((image, index) => {
        if (isNewImage(image) && image.image instanceof File) {
            uploadNewImage(image, index);
        } else if (isExistingImage(image)) {
            if (image.order == index) return;
            updateImage(index, image);
        }
    });
}

function deleteImages() {
    const token = `Bearer ${localStorage.getItem("access_token")}`;
    const images = imagesToDelete.value || [];

    images.forEach((imageId) => {
        axios
            .delete(
                `${page.props.ziggy.url}/api/admin/product-image/${imageId}`,
                {
                    headers: {
                        Authorization: token,
                    },
                }
            )
            .then(() => {
                imagesToDelete.value = imagesToDelete.value.filter(
                    (id) => id !== imageId
                );
            })
            .catch((error) => {
                if (error.response?.data?.error) {
                    openErrorDialog(error.response.data.error);
                }
            });
    });
}

const submit = () => {
    if (props.product?.id) {
        updateImages();
        deleteImages();

        form.transform((data) => {
            const formData = new FormData();
            Object.keys(data).forEach((key) => {
                if (key === "images") return;

                if (key === "categories") {
                    data.categories.forEach((category, index) => {
                        formData.append(`categories[${index}]`, category.id);
                    });
                } else if (key === "sizes") {
                    data.sizes.forEach((size, index) => {
                        formData.append(`sizes[${index}]`, size.id);
                    });
                } else if (key === "links") {
                    data.links.forEach((link, index) => {
                        if (link.platform_id) {
                            formData.append(
                                `links[${index}][platform_id]`,
                                link.platform_id
                            );
                        }

                        if (link.url) {
                            formData.append(`links[${index}][url]`, link.url);
                        }
                    });
                } else if (data[key] !== null && data[key] !== undefined) {
                    formData.append(key, data[key]);
                }
            });
            return formData;
        }).post(route("admin.product.update", props.product), {
            onError: (errors) => {
                if (errors.error) {
                    openErrorDialog(errors.error);
                }
            },
            onFinish: () => {
                form.reset();
            },
        });
    } else {
        form.transform((data) => {
            const formData = new FormData();
            Object.keys(data).forEach((key) => {
                if (key === "images") {
                    data[key].forEach((image, index) => {
                        if (image.image instanceof File) {
                            formData.append(`images[${index}]`, image);
                        }
                    });
                } else if (key === "categories") {
                    data.categories.forEach((category, index) => {
                        formData.append(`categories[${index}]`, category.id);
                    });
                } else if (key === "sizes") {
                    data.sizes.forEach((size, index) => {
                        formData.append(`sizes[${index}]`, size.id);
                    });
                } else if (key === "links") {
                    data.links.forEach((link, index) => {
                        formData.append(
                            `links[${index}][platform_id]`,
                            link.platform_id
                        );
                        formData.append(`links[${index}][url]`, link.url);
                    });
                } else if (data[key] !== null && data[key] !== undefined) {
                    formData.append(key, data[key]);
                }
            });
            return formData;
        }).post(route("admin.product.store"), {
            onError: (errors) => {
                if (errors.error) {
                    openErrorDialog(errors.error);
                }
            },
            onFinish: () => {
                form.reset();
            },
        });
    }
};

const imagesContainer = ref(null);

const draggable = useDraggable(imagesContainer, form.images, {
    animation: 150,
    onStart: (event) => {
        drag.value = true;
        const item = event.item;
        item.style.opacity = "0.2";
    },
    onEnd: (event) => {
        drag.value = false;
        const item = event.item;
        item.style.opacity = "1";
    },
});

const countNewImages = computed(() => {
    return form.images.filter((image) => isNewImage(image)).length;
});

const isNewImage = (image) => {
    return typeof image.id == "string" && image.id.startsWith("new-");
};

const isExistingImage = (image) => {
    return typeof image.id == "number" && typeof image.image == "string";
};

const imagesToDelete = ref([]);

const showAddLinkForm = ref(false);
const openAddLinkForm = () => {
    showAddLinkForm.value = true;
};
const linksContainer = ref(null);
const draggableLinks = useDraggable(linksContainer, form.links, {
    animation: 150,
    onStart: (event) => {
        drag.value = true;
        const item = event.item;
        item.style.opacity = "0.2";
    },
    onEnd: (event) => {
        drag.value = false;
        const item = event.item;
        item.style.opacity = "1";
    },
});

const getPlatformNameFromUrl = (url) => {
    return (
        url.match(/^(https?:\/\/)?(www\.)?([^\/]+)/)?.[3] ||
        "Platform Tidak Diketahui"
    );
};

const showErrorDialog = ref(false);
const errorMessage = ref(null);

const openErrorDialog = (message) => {
    errorMessage.value = message;
    showErrorDialog.value = true;
};
</script>

<template>
    <form @submit.prevent="submit" class="max-w-3xl">
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
                                    class="absolute p-[7px] text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100 transition-all duration-300 ease-in-out"
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
                <div ref="imagesContainer" class="flex flex-wrap w-full gap-2">
                    <ImageInput
                        v-for="(image, index) in form.images"
                        :key="image.id"
                        :id="`image-${image.id}`"
                        v-model="image.image"
                        type="file"
                        accept="image/*"
                        placeholder="Upload Produk"
                        class="!w-auto mt-1"
                        width="!w-[180px]"
                        height="h-[120px]"
                        :showDeleteButton="true"
                        :error="form.errors.images?.[index]"
                        :isDragging="drag"
                        @update:modelValue="
                            if (isNewImage(image)) {
                                image.image = $event;
                                form.images.push({
                                    id: `new-${countNewImages + 1}`,
                                    image: null,
                                });
                            } else {
                                image.image = $event;
                            }
                        "
                        @delete="
                            if (isNewImage(image)) {
                                form.images.splice(index, 1);
                            } else {
                                imagesToDelete.push(image.id);
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
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-50 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-200"
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
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-50 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-200"
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
                        class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-50 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-200"
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

            <!-- Links -->
            <div class="flex flex-col items-start w-full gap-2 mt-4">
                <h2 class="text-lg font-bold">Tautan Produk</h2>
                <div
                    ref="linksContainer"
                    class="flex flex-col items-start w-full gap-2"
                >
                    <div
                        v-for="(link, index) in form.links"
                        :key="index"
                        class="w-full"
                    >
                        <button
                            type="button"
                            class="flex items-center justify-start w-full max-w-sm gap-3 pl-3.5 pr-2 py-2 bg-white outline outline-gray-300 rounded-xl hover:outline-indigo-500 transition-all duration-200 ease-in-out cursor-pointer -outline-offset-2"
                            :class="{
                                'hover:outline-gray-300 ': drag,
                            }"
                            @click="link.showEditForm = true"
                        >
                            <img
                                v-if="link.platform?.icon"
                                :src="link.platform.icon"
                                alt="Platform Icon"
                                class="size-8"
                            />
                            <div v-else>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="25"
                                    height="24"
                                    viewBox="0 0 25 24"
                                    class="fill-gray-400 size-8"
                                >
                                    <path
                                        d="M12.1398 2C6.62577 2 2.13977 6.486 2.13977 12C2.13977 17.514 6.62577 22 12.1398 22C17.6538 22 22.1398 17.514 22.1398 12C22.1398 6.486 17.6538 2 12.1398 2ZM20.0708 11H17.3068C17.1824 8.81142 16.5701 6.67835 15.5148 4.757C16.7391 5.32882 17.7989 6.2011 18.5955 7.29266C19.3921 8.38422 19.8996 9.65957 20.0708 11ZM12.6698 4.027C13.7048 5.391 15.0968 7.807 15.2968 11H9.16977C9.30877 8.404 10.1638 5.972 11.6208 4.026C11.7928 4.016 11.9648 4 12.1398 4C12.3188 4 12.4938 4.016 12.6698 4.027ZM8.82777 4.727C7.84377 6.618 7.27577 8.762 7.16977 11H4.20877C4.38126 9.64775 4.89602 8.36189 5.70431 7.26416C6.51261 6.16643 7.58768 5.29315 8.82777 4.727ZM4.20877 13H7.18277C7.31877 15.379 7.84777 17.478 8.73877 19.23C7.52096 18.6559 6.4675 17.7842 5.67573 16.6953C4.88396 15.6064 4.37943 14.3355 4.20877 13ZM11.5898 19.973C10.1888 18.275 9.36177 15.896 9.18077 13H15.2938C15.0858 15.773 14.1768 18.196 12.6908 19.972C12.5088 19.984 12.3268 20 12.1398 20C11.9538 20 11.7728 19.984 11.5898 19.973ZM15.6008 19.201C16.5558 17.407 17.1388 15.3 17.2918 13H20.0698C19.9014 14.3244 19.4043 15.5857 18.6238 16.6688C17.8432 17.7519 16.8039 18.6224 15.6008 19.201Z"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <p class="font-medium text-gray-700 text-start">
                                    {{
                                        link.platform?.name ||
                                        getPlatformNameFromUrl(link.url)
                                    }}
                                </p>
                                <p
                                    class="flex-1 text-xs text-gray-800 line-clamp-1 overflow-ellipsis text-start"
                                >
                                    {{ link.url || "Tautan Tidak Diketahui" }}
                                </p>
                            </div>

                            <button
                                type="button"
                                class="p-[7px] text-gray-400 bg-white rounded-full hover:bg-gray-100 transition-all duration-300 ease-in-out"
                                @click="form.links.splice(index, 1)"
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
                        </button>
                        <DialogModal
                            :show="link.showEditForm"
                            title="Tambah Tautan Produk"
                            maxWidth="sm"
                            @close="link.showEditForm = false"
                        >
                            <template #content>
                                <ProductLinkForm
                                    :link="link"
                                    @submit="form.links[index] = $event"
                                    @close="link.showEditForm = false"
                                />
                            </template>
                        </DialogModal>
                    </div>
                </div>

                <PrimaryButton
                    type="button"
                    class="!px-3 !py-2 text-xs !text-orange-500 bg-yellow-50 hover:bg-yellow-100/80 active:bg-yellow-100/90 focus:bg-yellow-100 focus:ring-yellow-100 outline outline-orange-200 mt-0.5"
                    @click="openAddLinkForm"
                >
                    + Tambah Tautan Produk
                </PrimaryButton>
            </div>

            <PrimaryButton type="submit" class="mt-4">
                Simpan Data
            </PrimaryButton>
        </div>

        <DialogModal
            :show="showAddLinkForm"
            title="Tambah Tautan Produk"
            @close="showAddLinkForm = false"
            maxWidth="sm"
        >
            <template #content>
                <ProductLinkForm
                    :link="null"
                    @submit="form.links.push($event)"
                    @close="showAddLinkForm = false"
                />
            </template>
        </DialogModal>

        <ErrorDialog :show="showErrorDialog" @close="showErrorDialog = false">
            <template #content>
                <div>
                    <div
                        class="mb-1 text-lg font-medium text-center text-gray-900"
                    >
                        Terjadi Kesalahan
                    </div>
                    <p class="text-center text-gray-700">{{ errorMessage }}</p>
                </div>
            </template>
        </ErrorDialog>
    </form>
</template>
