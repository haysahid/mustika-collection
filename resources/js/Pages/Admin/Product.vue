<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AdminPagination from "@/Components/AdminPagination.vue";
import AdminItemAction from "@/Components/AdminItemAction.vue";
import DeleteConfirmationDialog from "@/Components/DeleteConfirmationDialog.vue";
import SuccessDialog from "@/Components/SuccessDialog.vue";

const products = ref([
    {
        id: 1,
        code: "PRD001",
        name: "Tas Kulit Premium",
        slug: "tas-kulit-premium",
        description: "Tas kulit asli dengan desain elegan dan tahan lama.",
        material: "Kulit Asli",
        selling_price: 350000,
        discount: 20,
        stock: 10,
        min_order: 1,
        unit: "pcs",
        color_id: 2,
        brand_id: 1,
        store_id: 1,
        disabled_at: null,
        created_at: "2024-06-01T10:00:00Z",
        updated_at: "2024-06-01T10:00:00Z",
        brand: {
            id: 1,
            name: "Brand A",
            description: "Deskripsi Brand A",
            logo: "../storage/brand/brand_1.png",
            website: "https://brand-a.com",
            created_at: "2024-06-01T10:00:00Z",
            updated_at: "2024-06-01T10:00:00Z",
        },
        images: [
            {
                id: 1,
                product_id: 1,
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        categories: [
            {
                id: 1,
                name: "Koko Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
            {
                id: 2,
                name: "Gamis Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        sizes: [
            {
                id: 1,
                name: "Besar",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        showDeleteModal: false,
    },
    {
        id: 2,
        code: "PRD001",
        name: "Tas Kulit Premium",
        slug: "tas-kulit-premium",
        description: "Tas kulit asli dengan desain elegan dan tahan lama.",
        material: "Kulit Asli",
        selling_price: 350000,
        discount: 20,
        stock: 10,
        min_order: 1,
        unit: "pcs",
        color_id: 2,
        brand_id: 1,
        store_id: 1,
        disabled_at: null,
        created_at: "2024-06-01T10:00:00Z",
        updated_at: "2024-06-01T10:00:00Z",
        brand: {
            id: 1,
            name: "Brand A",
            description: "Deskripsi Brand A",
            logo: "../storage/brand/brand_1.png",
            website: "https://brand-a.com",
            created_at: "2024-06-01T10:00:00Z",
            updated_at: "2024-06-01T10:00:00Z",
        },
        images: [
            {
                id: 1,
                product_id: 1,
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        categories: [
            {
                id: 1,
                name: "Koko Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
            {
                id: 2,
                name: "Gamis Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        sizes: [
            {
                id: 1,
                name: "Besar",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        showDeleteModal: false,
    },
    {
        id: 3,
        code: "PRD001",
        name: "Tas Kulit Premium",
        slug: "tas-kulit-premium",
        description: "Tas kulit asli dengan desain elegan dan tahan lama.",
        material: "Kulit Asli",
        selling_price: 350000,
        discount: 20,
        stock: 10,
        min_order: 1,
        unit: "pcs",
        color_id: 2,
        brand_id: 1,
        store_id: 1,
        disabled_at: null,
        created_at: "2024-06-01T10:00:00Z",
        updated_at: "2024-06-01T10:00:00Z",
        brand: {
            id: 1,
            name: "Brand A",
            description: "Deskripsi Brand A",
            logo: "../storage/brand/brand_1.png",
            website: "https://brand-a.com",
            created_at: "2024-06-01T10:00:00Z",
            updated_at: "2024-06-01T10:00:00Z",
        },
        images: [
            {
                id: 1,
                product_id: 1,
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        categories: [
            {
                id: 1,
                name: "Koko Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
            {
                id: 2,
                name: "Gamis Dewasa/Anak",
                image: "../storage/product/product_1.png",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        sizes: [
            {
                id: 1,
                name: "Besar",
                created_at: "2024-06-01T10:00:00Z",
                updated_at: "2024-06-01T10:00:00Z",
            },
        ],
        showDeleteModal: false,
    },
]);

const showDeleteProductDialog = (id) => {
    const product = products.value.find((item) => item.id === id);
    if (product) {
        product.showDeleteModal = true;
        console.log(`Deleting product with ID: ${product}`);
    }
};

const closeDeleteProductDialog = (id) => {
    const product = products.value.find((item) => item.id === id);
    if (product) {
        product.showDeleteModal = false;
    }
};

const showSuccessDialog = ref(false);
</script>

<template>
    <AdminLayout title="Produk" :showTitle="true">
        <template #icon>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="27"
                viewBox="0 0 26 27"
                class="fill-primary"
            >
                <rect opacity="0.01" y="0.695312" width="26" height="26" />
                <path
                    d="M12.1875 13.5111L3.25 9.75195V18.1261C3.25962 18.8391 3.68236 19.4817 4.33333 19.7728L12.1225 23.4453H12.1875V13.5111Z"
                />
                <path
                    d="M13 12.0701L22.2192 8.20256C22.064 8.03018 21.8762 7.89026 21.6667 7.79089L13.8667 4.14006C13.3181 3.8804 12.6819 3.8804 12.1333 4.14006L4.33332 7.79089C4.12376 7.89026 3.93598 8.03018 3.78082 8.20256L13 12.0701Z"
                />
                <path
                    d="M13.8125 13.5111V23.4453H13.8667L21.6667 19.7728C22.3141 19.4834 22.7362 18.846 22.75 18.137V9.75195L13.8125 13.5111Z"
                />
            </svg>
        </template>

        <div>
            <PrimaryButton
                type="button"
                class="bg-yellow-500 hover:bg-yellow-500/80 active:bg-yellow-500/90 focus:bg-yellow-500 focus:ring-yellow-500 max-sm:text-xs max-sm:px-4 max-sm:py-2"
                @click="$inertia.visit(route('admin.product.add'))"
            >
                + Tambah Data</PrimaryButton
            >

            <!-- Table -->
            <div class="mt-4 overflow-x-auto rounded-t-lg sm:mt-6">
                <table class="table-default">
                    <thead>
                        <tr>
                            <th class="w-12">No</th>
                            <th>Nama Barang</th>
                            <th class="min-w-[150px] w-[200px]">Foto</th>
                            <th>Harga</th>
                            <th>Kategori Produk</th>
                            <th>Jumlah Produk</th>
                            <th class="w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(product, index) in products"
                            :key="product.id"
                        >
                            <td>
                                {{ index + 1 }}
                            </td>
                            <td>
                                {{ product.name }}
                            </td>
                            <td>
                                <img
                                    v-if="product.images.length > 0"
                                    :src="product.images[0].image"
                                    :alt="product.name"
                                    class="object-cover h-[80px] sm:h-[120px] aspect-square rounded"
                                />
                            </td>
                            <td class="text-center">
                                <p
                                    v-if="product.discount > 0"
                                    class="text-red-500 line-through"
                                >
                                    Rp
                                    {{ product.selling_price.toLocaleString() }}
                                </p>
                                <p>
                                    Rp
                                    {{
                                        (
                                            product.selling_price *
                                            (1 - product.discount / 100)
                                        ).toLocaleString()
                                    }}
                                </p>
                            </td>
                            <td class="text-center">
                                <div
                                    class="flex flex-wrap gap-1.5 justify-center items-center"
                                >
                                    <div
                                        v-for="category in product.categories"
                                        :key="category.id"
                                        class="inline-block px-2 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs text-blue-700 bg-blue-100 rounded-full"
                                        :class="{
                                            'bg-pink-100 text-pink-700':
                                                category.id === 2,
                                        }"
                                    >
                                        {{ category.name }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{ product.stock }}
                                <span>{{ product.unit }}</span>
                            </td>
                            <td>
                                <AdminItemAction
                                    @edit="
                                        $inertia.visit(
                                            route('admin.product.edit', {
                                                id: product.id,
                                            })
                                        )
                                    "
                                    @delete="
                                        showDeleteProductDialog(product.id)
                                    "
                                />
                                <DeleteConfirmationDialog
                                    :show="product.showDeleteModal"
                                    @close="
                                        closeDeleteProductDialog(product.id)
                                    "
                                    @delete="
                                        // Logic to delete the product
                                        console.log(
                                            `Deleting product with ID: ${product.id}`
                                        );
                                        closeDeleteProductDialog(product.id);
                                        showSuccessDialog = true;
                                    "
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <SuccessDialog
                    :show="showSuccessDialog"
                    title="Data Berhasil Dihapus"
                    @close="showSuccessDialog = false"
                />
            </div>

            <!-- Pagination -->
            <AdminPagination />
        </div>
    </AdminLayout>
</template>
