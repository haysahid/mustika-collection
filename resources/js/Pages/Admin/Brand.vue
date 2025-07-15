<script setup>
import { ref, onMounted } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AdminPagination from "@/Components/AdminPagination.vue";
import AdminItemAction from "@/Components/AdminItemAction.vue";
import DeleteConfirmationDialog from "@/Components/DeleteConfirmationDialog.vue";
import SuccessDialog from "@/Components/SuccessDialog.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    brands: null,
});

const brands = ref(
    props.brands.data.map((brand) => ({
        ...brand,
        logo: brand.logo ? "/storage/" + brand.logo : null,
        showDeleteModal: false,
    }))
);

const filters = useForm({
    search: null,
});

const getQueryParams = () => {
    filters.search = route().params.search;
};
getQueryParams();

function getBrands() {
    let queryParams = {};

    if (filters.search) queryParams.search = filters.search;

    router.get(route("admin.brand"), queryParams, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            getQueryParams();
            brands.value = props.brands.data.map((brand) => ({
                ...brand,
                logo: brand.logo ? "/storage/" + brand.logo : null,
                showDeleteModal: false,
            }));
        },
    });
}

const showDeleteBrandDialog = (brand) => {
    if (brand) {
        brand.showDeleteModal = true;
        console.log(`Deleting brand with ID: ${brand.id}`);
    }
};

const closeDeleteBrandDialog = (brand, result) => {
    if (brand) {
        brand.showDeleteModal = false;
        if (result) {
            openSuccessDialog("Data Berhasil Dihapus");
            brands.value = brands.value.filter((b) => b.id !== brand.id);
        }
    }
};

const deleteBrand = (brand) => {
    if (brand) {
        const form = useForm();
        form.delete(
            route("admin.brand.destroy", {
                brand: brand,
            }),
            {
                onError: (errors) => {
                    openErrorDialog(errors.error);
                },
                onSuccess: () => {
                    closeDeleteBrandDialog(brand, true);
                },
            }
        );
    }
};

const showSuccessDialog = ref(false);
const successMessage = ref("Berhasil");

const openSuccessDialog = (message) => {
    successMessage.value = message;
    showSuccessDialog.value = true;
};

const showErrorDialog = ref(false);
const errorMessage = ref("");

const openErrorDialog = (message) => {
    errorMessage.value = message;
    showErrorDialog.value = true;
};

const page = usePage();

onMounted(() => {
    if (page.props.flash.success) {
        openSuccessDialog(page.props.flash.success);
    }
});
</script>

<template>
    <AdminLayout title="Brand" :showTitle="true">
        <template #icon>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="27"
                viewBox="0 0 26 27"
                class="fill-primary"
            >
                <path
                    d="M5.95829 8.27799C5.52732 8.27799 5.11399 8.10679 4.80924 7.80204C4.5045 7.4973 4.33329 7.08397 4.33329 6.65299C4.33329 6.22202 4.5045 5.80869 4.80924 5.50395C5.11399 5.1992 5.52732 5.02799 5.95829 5.02799C6.38927 5.02799 6.80259 5.1992 7.10734 5.50395C7.41209 5.80869 7.58329 6.22202 7.58329 6.65299C7.58329 7.08397 7.41209 7.4973 7.10734 7.80204C6.80259 8.10679 6.38927 8.27799 5.95829 8.27799ZM23.1941 13.2397L13.4441 3.48966C13.0541 3.09966 12.5125 2.86133 11.9166 2.86133H4.33329C3.13079 2.86133 2.16663 3.82549 2.16663 5.02799V12.6113C2.16663 13.2072 2.40496 13.7488 2.80579 14.1388L12.545 23.8888C12.9458 24.2788 13.4875 24.528 14.0833 24.528C14.6791 24.528 15.2208 24.2788 15.6108 23.8888L23.1941 16.3055C23.595 15.9155 23.8333 15.3738 23.8333 14.778C23.8333 14.1713 23.5841 13.6297 23.1941 13.2397Z"
                />
            </svg>
        </template>

        <div>
            <div class="flex items-center justify-between gap-4">
                <PrimaryButton
                    type="button"
                    class="bg-yellow-500 hover:bg-yellow-500/80 active:bg-yellow-500/90 focus:bg-yellow-500 focus:ring-yellow-500 max-sm:text-xs max-sm:px-4 max-sm:py-2"
                    @click="$inertia.visit(route('admin.brand.create'))"
                >
                    + Tambah Data
                </PrimaryButton>
                <TextInput
                    v-model="filters.search"
                    placeholder="Cari brand..."
                    bgClass="bg-gray-100"
                    textClass="text-sm sm:text-base"
                    @keyup.enter="getBrands()"
                >
                    <template #suffix>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            class="absolute -translate-y-1/2 fill-gray-400 right-3 top-1/2 size-5"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M11 17C11.7879 17 12.5681 16.8448 13.2961 16.5433C14.0241 16.2417 14.6855 15.7998 15.2426 15.2426C15.7998 14.6855 16.2417 14.0241 16.5433 13.2961C16.8448 12.5681 17 11.7879 17 11C17 10.2121 16.8448 9.43185 16.5433 8.7039C16.2417 7.97595 15.7998 7.31451 15.2426 6.75736C14.6855 6.20021 14.0241 5.75825 13.2961 5.45672C12.5681 5.15519 11.7879 5 11 5C9.4087 5 7.88258 5.63214 6.75736 6.75736C5.63214 7.88258 5 9.4087 5 11C5 12.5913 5.63214 14.1174 6.75736 15.2426C7.88258 16.3679 9.4087 17 11 17ZM11 19C13.1217 19 15.1566 18.1571 16.6569 16.6569C18.1571 15.1566 19 13.1217 19 11C19 8.87827 18.1571 6.84344 16.6569 5.34315C15.1566 3.84285 13.1217 3 11 3C8.87827 3 6.84344 3.84285 5.34315 5.34315C3.84285 6.84344 3 8.87827 3 11C3 13.1217 3.84285 15.1566 5.34315 16.6569C6.84344 18.1571 8.87827 19 11 19Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M15.3201 15.2903C15.5082 15.1035 15.7629 14.9991 16.0281 15C16.2933 15.0009 16.5472 15.1072 16.7341 15.2953L20.7091 19.2953C20.8908 19.4844 20.9909 19.7373 20.9879 19.9995C20.9849 20.2618 20.879 20.5123 20.6931 20.6972C20.5071 20.8822 20.256 20.9866 19.9937 20.9881C19.7315 20.9896 19.4791 20.8881 19.2911 20.7053L15.3161 16.7053C15.1291 16.5172 15.0245 16.2626 15.0253 15.9975C15.026 15.7323 15.1321 15.4783 15.3201 15.2913V15.2903Z"
                            />
                        </svg>
                    </template>
                </TextInput>
            </div>

            <!-- Table -->
            <div class="mt-4 overflow-x-auto rounded-t-lg sm:mt-6">
                <table class="table-default">
                    <thead>
                        <tr>
                            <th class="w-12">No</th>
                            <th class="min-w-[150px] w-[200px]">Logo Brand</th>
                            <th>Nama Brand</th>
                            <th>Deskripsi</th>
                            <th class="w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(brand, index) in brands" :key="brand.id">
                            <td>
                                {{
                                    index +
                                    1 +
                                    (props.brands.current_page - 1) *
                                        props.brands.per_page
                                }}
                            </td>
                            <td>
                                <img
                                    v-if="brand.logo"
                                    :src="brand.logo"
                                    alt="Logo Brand"
                                    class="object-contain h-[80px] sm:h-[120px] rounded aspect-[3/2]"
                                />
                                <div
                                    v-else
                                    class="flex items-center justify-center h-[80px] sm:h-[120px] bg-gray-100 rounded aspect-[3/2]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        class="size-6 fill-gray-400"
                                    >
                                        <path
                                            d="M5 21C4.45 21 3.97933 20.8043 3.588 20.413C3.19667 20.0217 3.00067 19.5507 3 19V5C3 4.45 3.196 3.97933 3.588 3.588C3.98 3.19667 4.45067 3.00067 5 3H19C19.55 3 20.021 3.196 20.413 3.588C20.805 3.98 21.0007 4.45067 21 5V19C21 19.55 20.8043 20.021 20.413 20.413C20.0217 20.805 19.5507 21.0007 19 21H5ZM6 17H18L14.25 12L11.25 16L9 13L6 17Z"
                                        />
                                    </svg>
                                </div>
                            </td>
                            <td>
                                {{ brand.name }}
                            </td>
                            <td class="!whitespace-normal">
                                <p class="line-clamp-2">
                                    {{ brand.description }}
                                </p>
                            </td>
                            <td>
                                <AdminItemAction
                                    @edit="
                                        $inertia.visit(
                                            route('admin.brand.edit', {
                                                brand: brand,
                                            })
                                        )
                                    "
                                    @delete="showDeleteBrandDialog(brand)"
                                />
                                <DeleteConfirmationDialog
                                    :show="brand.showDeleteModal"
                                    :title="`Hapus Brand <b>${brand.name}</b>?`"
                                    @close="closeDeleteBrandDialog(brand)"
                                    @delete="deleteBrand(brand)"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <SuccessDialog
                    :show="showSuccessDialog"
                    :title="successMessage"
                    @close="showSuccessDialog = false"
                />

                <ErrorDialog
                    :show="showErrorDialog"
                    @close="showErrorDialog = false"
                >
                    <template #content>
                        <div>
                            <div
                                class="mb-1 text-lg font-medium text-center text-gray-900"
                            >
                                Terjadi Kesalahan
                            </div>
                            <p class="text-center text-gray-700">
                                {{ errorMessage }}
                            </p>
                        </div>
                    </template>
                </ErrorDialog>
            </div>

            <!-- Pagination -->
            <AdminPagination :links="props.brands.links" />
        </div>
    </AdminLayout>
</template>
