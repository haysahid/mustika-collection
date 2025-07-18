<script setup lang="ts">
import { ref, computed, defineExpose, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import ColorChip from "@/Components/ColorChip.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Chip from "@/Components/Chip.vue";
import ProductLinkDialog from "@/Components/ProductLinkDialog.vue";
import TextInput from "@/Components/TextInput.vue";
import SuccessDialog from "@/Components/SuccessDialog.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";
import { useCartStore } from "@/stores/cart-store";
import QuantityInput from "@/Components/QuantityInput.vue";

const cartStore = useCartStore();

const page = usePage();
const store = page.props.store as StoreEntity;

const props = defineProps({
    product: {
        type: Object as () => ProductEntity,
        required: true,
    },
    motifs: {
        type: Array as () => string[],
        default: () => [],
    },
    colors: {
        type: Array as () => ColorEntity[],
        default: () => [],
    },
    sizes: {
        type: Array as () => SizeEntity[],
        default: () => [],
    },
});

const variants = props.product.variants || [];

const filter = useForm(
    variants.length == 1
        ? {
              motif: JSON.parse(JSON.stringify(variants[0]?.motif)),
              color: JSON.parse(JSON.stringify(variants[0]?.color)),
              size: JSON.parse(JSON.stringify(variants[0]?.size)),
          }
        : {
              motif: null,
              color: null,
              size: null,
          }
);

// Set initial filter based on URL parameters
if (route().params) {
    const sku = route().params.sku;
    const variant_id = route().params.variant_id;

    const variant = variants.find((v) => v.sku === sku || v.id === variant_id);
    if (variant) {
        filter.motif = variant.motif;
        filter.color = variant.color;
        filter.size = variant.size;
    }
}

const filteredVariants = computed(() => {
    return variants.filter((variant) => {
        return filter.motif ? variant.motif === filter.motif : true;
    });
});

const availableMotifs = computed(() => {
    return props.motifs.filter((motif) =>
        filteredVariants.value.some((variant) => variant.motif === motif)
    );
});

const availableColors = computed(() => {
    return props.colors.filter((color) =>
        filteredVariants.value.some((variant) => variant.color?.id === color.id)
    );
});

const availableSizes = computed(() => {
    return props.sizes.filter((size) =>
        filteredVariants.value.some((variant) => variant.size?.id === size.id)
    );
});

const selectedVariant = computed(() => {
    return (
        variants.find(
            (variant) =>
                variant.motif === filter.motif &&
                variant.color?.id === filter.color?.id &&
                variant.size?.id === filter.size?.id
        ) || null
    );
});

const availableStock = computed(() => {
    return selectedVariant.value?.current_stock_level || 0;
});

const quantity = ref(1);

const isVariantInCart = computed(() => {
    const item = cartStore.getItemByVariant(selectedVariant.value);
    quantity.value = item ? item.quantity : 1;
    return !!item;
});

function addToCart() {
    if (!selectedVariant.value) {
        openErrorDialog(
            "Silakan pilih motif, warna, dan ukuran produk terlebih dahulu."
        );
        return;
    }

    if (quantity.value <= 0 || quantity.value > availableStock.value) {
        openErrorDialog("Jumlah yang dipilih tidak valid.");
        return;
    }

    const existingCartItem = cartStore.getItemByVariant(selectedVariant.value);

    if (existingCartItem) {
        cartStore.updateItem({
            ...existingCartItem,
            quantity: quantity.value,
        });

        openSuccessDialog(`Berhasil memperbarui jumlah produk di keranjang.`);
        return;
    }

    const cartItem: CartItemModel = {
        product_id: props.product.id,
        variant_id: selectedVariant.value.id,
        quantity: quantity.value,
        image:
            selectedVariant.value.images[0]?.image ||
            props.product.images[0]?.image,
        variant: selectedVariant.value,
        created_at: new Date().toISOString(),
        selected: true,
    };

    cartStore.addItem(cartItem);

    openSuccessDialog(`Berhasil menambahkan produk ke keranjang.`);
}

const showSuccessDialog = ref(false);
const successMessage = ref(null);

function openSuccessDialog(message) {
    successMessage.value = message;
    showSuccessDialog.value = true;
}

function closeSuccessDialog() {
    showSuccessDialog.value = false;
    successMessage.value = null;
}

const showErrorDialog = ref(false);
const errorMessage = ref(null);

function openErrorDialog(message) {
    errorMessage.value = message;
    showErrorDialog.value = true;
}

function closeErrorDialog() {
    showErrorDialog.value = false;
    errorMessage.value = null;
}

// Links
function linkWhatsApp() {
    const phone = store.phone?.replace(/[^0-9]/g, "");
    return `https://wa.me/${phone}?text=Halo, saya tertarik dengan produk "${
        props.product.name
    }". \n\nLink produk: ${route("product.show", props.product.slug)}`;
}

const showProductLinkDialog = ref(false);
function openProductLinkDialog() {
    showProductLinkDialog.value = true;
}
function closeProductLinkDialog() {
    showProductLinkDialog.value = false;
}

console.log("Selected Variant:", selectedVariant.value);

defineExpose({
    filter,
    selectedVariant,
    filteredVariants,
});
</script>

<template>
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4">
            <!-- Motifs -->
            <div class="flex gap-4">
                <InputLabel
                    for="motif"
                    value="Motif"
                    class="!text-gray-500 min-w-[80px] py-3 h-min !text-base"
                />
                <div class="flex flex-wrap gap-2.5">
                    <Chip
                        v-for="(motif, index) in props.motifs"
                        :key="index"
                        :label="motif"
                        :selected="filter.motif === motif"
                        @click="
                            filter.motif === motif
                                ? (filter.motif = null)
                                : (filter.motif = motif);

                            if (
                                filter.size &&
                                !filteredVariants.some(
                                    (variant) =>
                                        variant.size?.id === filter.size.id
                                )
                            ) {
                                filter.size = null;
                            }

                            if (
                                filter.color &&
                                !filteredVariants.some(
                                    (variant) =>
                                        variant.color?.id === filter.color.id
                                )
                            ) {
                                filter.color = null;
                            }
                        "
                    />
                </div>
            </div>

            <!-- Colors -->
            <div class="flex gap-4">
                <InputLabel
                    for="motif"
                    value="Warna"
                    class="!text-gray-500 min-w-[80px] py-3 h-min !text-base"
                />
                <div class="flex flex-wrap gap-2.5">
                    <ColorChip
                        v-for="(color, index) in props.colors"
                        :key="index"
                        :label="color.name"
                        :hexCode="color.hex_code"
                        :selected="filter.color?.id === color.id"
                        :disabled="!availableColors.includes(color)"
                        @click="
                            filter.color?.id === color.id
                                ? (filter.color = null)
                                : (filter.color = color);

                            if (
                                filter.size &&
                                !filteredVariants.some(
                                    (variant) =>
                                        variant.size?.id === filter.size.id
                                )
                            ) {
                                filter.size = null;
                            }

                            if (
                                filter.motif &&
                                !filteredVariants.some(
                                    (variant) => variant.motif === filter.motif
                                )
                            ) {
                                filter.motif = null;
                            }
                        "
                    />
                </div>
            </div>

            <!-- Sizes -->
            <div class="flex gap-4">
                <InputLabel
                    for="size"
                    value="Ukuran"
                    class="!text-gray-500 min-w-[80px] py-3 h-min !text-base"
                />
                <div class="flex flex-wrap gap-2.5">
                    <Chip
                        v-for="(size, index) in props.sizes"
                        :key="index"
                        :label="size.name"
                        :selected="filter.size?.id === size.id"
                        :disabled="!availableSizes.includes(size)"
                        @click="
                            filter.size?.id === size.id
                                ? (filter.size = null)
                                : (filter.size = size);

                            if (
                                filter.color &&
                                !filteredVariants.some(
                                    (variant) =>
                                        variant.color?.id === filter.color.id
                                )
                            ) {
                                filter.color = null;
                            }

                            if (
                                filter.motif &&
                                !filteredVariants.some(
                                    (variant) => variant.motif === filter.motif
                                )
                            ) {
                                filter.motif = null;
                            }
                        "
                    />
                </div>
            </div>

            <!-- Quantity -->
            <QuantityInput
                v-model="quantity"
                :unit="selectedVariant?.unit"
                :max="availableStock"
            />
        </div>

        <!-- Call to Actions -->
        <div>
            <p v-if="isVariantInCart" class="mb-3 text-sm text-gray-500">
                *Produk sudah ada di keranjang
            </p>
            <div
                class="flex flex-col items-start gap-4 sm:flex-row sm:items-center"
            >
                <!-- Add to Cart -->
                <button
                    class="flex items-center justify-center w-full gap-2 px-6 py-3 transition duration-200 rounded-lg bg-primary hover:bg-primary-dark sm:w-auto"
                    @click="addToCart"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        class="fill-white"
                    >
                        <path
                            d="M17 18.6948C15.89 18.6948 15 19.5848 15 20.6948C15 21.2253 15.2107 21.734 15.5858 22.109C15.9609 22.4841 16.4696 22.6948 17 22.6948C17.5304 22.6948 18.0391 22.4841 18.4142 22.109C18.7893 21.734 19 21.2253 19 20.6948C19 20.1644 18.7893 19.6557 18.4142 19.2806C18.0391 18.9055 17.5304 18.6948 17 18.6948ZM1 2.69482V4.69482H3L6.6 12.2848L5.24 14.7348C5.09 15.0148 5 15.3448 5 15.6948C5 16.2253 5.21071 16.734 5.58579 17.109C5.96086 17.4841 6.46957 17.6948 7 17.6948H19V15.6948H7.42C7.3537 15.6948 7.29011 15.6685 7.24322 15.6216C7.19634 15.5747 7.17 15.5111 7.17 15.4448C7.17 15.3948 7.18 15.3548 7.2 15.3248L8.1 13.6948H15.55C16.3 13.6948 16.96 13.2748 17.3 12.6648L20.88 6.19482C20.95 6.03482 21 5.86482 21 5.69482C21 5.42961 20.8946 5.17525 20.7071 4.98772C20.5196 4.80018 20.2652 4.69482 20 4.69482H5.21L4.27 2.69482M7 18.6948C5.89 18.6948 5 19.5848 5 20.6948C5 21.2253 5.21071 21.734 5.58579 22.109C5.96086 22.4841 6.46957 22.6948 7 22.6948C7.53043 22.6948 8.03914 22.4841 8.41421 22.109C8.78929 21.734 9 21.2253 9 20.6948C9 20.1644 8.78929 19.6557 8.41421 19.2806C8.03914 18.9055 7.53043 18.6948 7 18.6948Z"
                        />
                    </svg>
                    <p class="font-semibold text-white">
                        {{
                            isVariantInCart
                                ? "Perbarui Keranjang"
                                : "Masukkan Keranjang"
                        }}
                    </p>
                </button>

                <!-- WhatsApp -->
                <a
                    :href="linkWhatsApp()"
                    target="_blank"
                    class="w-full sm:w-auto"
                >
                    <button
                        class="flex items-center justify-center w-full gap-2 px-6 py-3 transition duration-200 bg-green-600 rounded-lg hover:bg-green-700 sm:w-auto"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="25"
                            viewBox="0 0 24 25"
                            class="fill-white"
                        >
                            <path
                                d="M19.0498 5.60488C18.1329 4.67898 17.0408 3.94484 15.8373 3.44524C14.6338 2.94564 13.3429 2.69056 12.0398 2.69488C6.5798 2.69488 2.1298 7.14488 2.1298 12.6049C2.1298 14.3549 2.5898 16.0549 3.4498 17.5549L2.0498 22.6949L7.2998 21.3149C8.7498 22.1049 10.3798 22.5249 12.0398 22.5249C17.4998 22.5249 21.9498 18.0749 21.9498 12.6149C21.9498 9.96488 20.9198 7.47488 19.0498 5.60488ZM12.0398 20.8449C10.5598 20.8449 9.1098 20.4449 7.8398 19.6949L7.5398 19.5149L4.4198 20.3349L5.2498 17.2949L5.0498 16.9849C4.22735 15.6719 3.79073 14.1542 3.7898 12.6049C3.7898 8.06488 7.4898 4.36488 12.0298 4.36488C14.2298 4.36488 16.2998 5.22488 17.8498 6.78488C18.6174 7.54874 19.2257 8.45742 19.6394 9.45821C20.0531 10.459 20.264 11.532 20.2598 12.6149C20.2798 17.1549 16.5798 20.8449 12.0398 20.8449ZM16.5598 14.6849C16.3098 14.5649 15.0898 13.9649 14.8698 13.8749C14.6398 13.7949 14.4798 13.7549 14.3098 13.9949C14.1398 14.2449 13.6698 14.8049 13.5298 14.9649C13.3898 15.1349 13.2398 15.1549 12.9898 15.0249C12.7398 14.9049 11.9398 14.6349 10.9998 13.7949C10.2598 13.1349 9.7698 12.3249 9.6198 12.0749C9.4798 11.8249 9.5998 11.6949 9.7298 11.5649C9.8398 11.4549 9.9798 11.2749 10.0998 11.1349C10.2198 10.9949 10.2698 10.8849 10.3498 10.7249C10.4298 10.5549 10.3898 10.4149 10.3298 10.2949C10.2698 10.1749 9.7698 8.95488 9.5698 8.45488C9.3698 7.97488 9.1598 8.03488 9.0098 8.02488H8.5298C8.3598 8.02488 8.0998 8.08488 7.8698 8.33488C7.6498 8.58488 7.0098 9.18488 7.0098 10.4049C7.0098 11.6249 7.89981 12.8049 8.0198 12.9649C8.1398 13.1349 9.7698 15.6349 12.2498 16.7049C12.8398 16.9649 13.2998 17.1149 13.6598 17.2249C14.2498 17.4149 14.7898 17.3849 15.2198 17.3249C15.6998 17.2549 16.6898 16.7249 16.8898 16.1449C17.0998 15.5649 17.0998 15.0749 17.0298 14.9649C16.9598 14.8549 16.8098 14.8049 16.5598 14.6849Z"
                            />
                        </svg>
                        <p class="font-semibold text-white">WhatsApp</p>
                    </button>
                </a>
            </div>
        </div>

        <SuccessDialog
            :show="showSuccessDialog"
            :title="successMessage"
            @close="closeSuccessDialog"
        />

        <ErrorDialog
            :show="showErrorDialog"
            :title="errorMessage"
            @close="closeErrorDialog"
        />

        <ProductLinkDialog
            :show="showProductLinkDialog"
            :links="
                props.product.links.map(function (link) {
                    if (!link.platform) return link;
                    return {
                        ...link,
                        platform: {
                            ...link.platform,
                            icon: '/storage/' + link.platform.icon,
                        },
                    };
                })
            "
            @close="closeProductLinkDialog"
        />
    </div>
</template>
