<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessDialog from "@/Components/SuccessDialog.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";
import Dropdown from "@/Components/Dropdown.vue";
import TextInput from "@/Components/TextInput.vue";
import SalesReportTable from "./Report/SalesReportTable.vue";
import PurchaseReportTable from "./Report/PurchaseReportTable.vue";
import axios from "axios";
import StockReportTable from "./Report/StockReportTable.vue";
import LoadingDialog from "@/Components/LoadingDialog.vue";

const props = defineProps({
    brands: null,

    title: null,
    report_type: null,
    start_date: null,
    end_date: null,
    printed_at: null,
    report: null,
    totals: null,
    brand: null,
});

const selectedReportType = ref({ label: "Penjualan", value: "sale" });
const reportTypes = [
    { label: "Penjualan", value: "sale" },
    { label: "Stok", value: "stock" },
];
const reportTypeSearch = ref("");
const isReportTypeDropdownOpen = ref(false);

const filteredReportTypes = computed(() => {
    return reportTypes.filter((type) =>
        type.label.toLowerCase().includes(reportTypeSearch.value.toLowerCase())
    );
});

const selectedBrand = ref(null);
const brands = props.brands || [];
const brandSearch = ref("");
const isBrandDropdownOpen = ref(false);
const filteredBrands = computed(() => {
    return brands.filter((brand) =>
        brand.name.toLowerCase().includes(brandSearch.value.toLowerCase())
    );
});

const startDate = ref(null);
const endDate = ref(null);

const startDateInput = ref(null);
const endDateInput = ref(null);

const isStockReport = computed(() => {
    return selectedReportType.value.value === "stock";
});

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

const printStatus = ref(null);

function isMobileDevice() {
    return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
}

function printReport() {
    printStatus.value = "loading";

    const token = `Bearer ${localStorage.getItem("access_token")}`;

    const reportType = selectedReportType.value.value;
    const brand = selectedBrand.value ? selectedBrand.value.name : undefined;
    const start = startDate.value ? startDate.value : undefined;
    const end = endDate.value ? endDate.value : undefined;

    axios
        .post(
            route("api.admin.report.generate"),
            {
                report_type: reportType,
                brand: brand,
                start_date: start,
                end_date: end,
            },
            {
                headers: {
                    Authorization: token,
                    Accept: "application/pdf",
                },
            }
        )
        .then((response) => {
            if (response.data && response.data.result.pdf) {
                printStatus.value = "success";

                try {
                    // Create blob from base64
                    const byteCharacters = atob(response.data.result.pdf);
                    const byteArrays = [];

                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteArrays.push(byteCharacters.charCodeAt(i));
                    }

                    const byteArray = new Uint8Array(byteArrays);

                    const url = window.URL.createObjectURL(
                        new Blob([byteArray], { type: "application/pdf" })
                    );

                    // Remove iframe if exists
                    const oldIframe = document.querySelector("iframe");
                    if (oldIframe) {
                        oldIframe.remove();
                    }

                    if (isMobileDevice()) {
                        // For mobile devices, open in new tab
                        window.open(url, "_blank");
                        return;
                    } else {
                        // Create iframe
                        const iframe = document.createElement("iframe");
                        iframe.style.display = "none";
                        iframe.src = url;
                        document.body.appendChild(iframe);

                        // Print when iframe is loaded
                        iframe.onload = function () {
                            iframe.contentWindow.print();
                        };
                    }

                    // Revoke object URL after printing
                    window.URL.revokeObjectURL(url);
                } catch (error) {
                    printStatus.value = "error";
                    openErrorDialog("Gagal mengunduh laporan.");
                }
            } else {
                printStatus.value = "error";
                openErrorDialog("Gagal mengunduh laporan.");
            }
        })
        .catch((error) => {
            printStatus.value = "error";
            openErrorDialog(
                error.response?.data?.message ||
                    "Terjadi kesalahan saat mengunduh laporan."
            );
        });
}

onMounted(() => {
    if (page.props.flash.success) {
        openSuccessDialog(page.props.flash.success);
    }

    selectedReportType.value = route().params.report_type
        ? reportTypes.find(
              (type) => type.value === route().params.report_type
          ) || selectedReportType.value
        : selectedReportType.value;
    selectedBrand.value = route().params.brand
        ? brands.find((brand) => brand.name === route().params.brand) ||
          selectedBrand.value
        : selectedBrand.value;
    startDate.value = route().params.start_date || null;
    endDate.value = route().params.end_date || null;
});
</script>

<template>
    <AdminLayout title="Laporan" :showTitle="true">
        <template #icon>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="27"
                viewBox="0 0 26 27"
                class="fill-primary"
            >
                <path
                    d="M21.6667 9.36133L15.1667 2.86133H6.50004C5.9254 2.86133 5.3743 3.0896 4.96798 3.49593C4.56165 3.90226 4.33337 4.45336 4.33337 5.02799V22.3613C4.33337 22.936 4.56165 23.4871 4.96798 23.8934C5.3743 24.2997 5.9254 24.528 6.50004 24.528H19.5C20.0747 24.528 20.6258 24.2997 21.0321 23.8934C21.4384 23.4871 21.6667 22.936 21.6667 22.3613V9.36133ZM9.75004 21.278H7.58337V11.528H9.75004V21.278ZM14.0834 21.278H11.9167V14.778H14.0834V21.278ZM18.4167 21.278H16.25V18.028H18.4167V21.278ZM15.1667 10.4447H14.0834V5.02799L19.5 10.4447H15.1667Z"
                />
            </svg>
        </template>

        <div>
            <div class="flex items-center justify-between gap-4 print:hidden">
                <PrimaryButton
                    type="button"
                    class="bg-yellow-500 hover:bg-yellow-500/80 active:bg-yellow-500/90 focus:bg-yellow-500 focus:ring-yellow-500 max-sm:text-xs max-sm:px-4 max-sm:py-2"
                    :disabled="printStatus === 'loading'"
                    @click="printReport()"
                >
                    Cetak Laporan
                </PrimaryButton>
                <div class="flex items-center gap-2">
                    <!-- Report Type -->
                    <Dropdown
                        v-if="reportTypes"
                        id="report_type"
                        v-model="selectedReportType"
                        :options="filteredReportTypes"
                        option-label="label"
                        option-value="value"
                        placeholder="Jenis Laporan"
                        align="left"
                        required
                        class="hidden max-w-48 sm:block"
                        @onOpen="isReportTypeDropdownOpen = true"
                        @onClose="isReportTypeDropdownOpen = false"
                    >
                        <template #trigger>
                            <TextInput
                                :modelValue="
                                    selectedReportType &&
                                    !isReportTypeDropdownOpen
                                        ? selectedReportType?.label
                                        : reportTypeSearch
                                "
                                @update:modelValue="
                                    selectedReportType &&
                                    !isReportTypeDropdownOpen
                                        ? null
                                        : (reportTypeSearch = $event)
                                "
                                class="w-full"
                                textClass="text-sm sm:text-base"
                                :bgClass="
                                    !selectedReportType ? 'bg-gray-100' : ''
                                "
                                placeholder="Jenis Laporan"
                            >
                                <template #suffix>
                                    <button
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
                                    v-for="(
                                        reportType, index
                                    ) in filteredReportTypes"
                                    :key="index"
                                    @click="
                                        isReportTypeDropdownOpen = false;
                                        selectedReportType = reportType;
                                        reportTypeSearch = '';

                                        $inertia.get(route('admin.report'), {
                                            report_type: reportType.value,
                                            brand: selectedBrand
                                                ? selectedBrand.name
                                                : undefined,
                                            start_date:
                                                startDate && !isStockReport
                                                    ? startDate
                                                    : undefined,
                                            end_date:
                                                endDate && !isStockReport
                                                    ? endDate
                                                    : undefined,
                                        });
                                    "
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                >
                                    {{ reportType.label }}
                                </li>
                            </ul>
                        </template>
                    </Dropdown>

                    <!-- Brand -->
                    <Dropdown
                        v-if="isStockReport && brands"
                        id="brand"
                        v-model="selectedBrand"
                        :options="filteredBrands"
                        option-label="name"
                        option-value="id"
                        placeholder="Pilih Brand"
                        align="left"
                        required
                        class="max-w-48"
                        @onOpen="isBrandDropdownOpen = true"
                        @onClose="isBrandDropdownOpen = false"
                    >
                        <template #trigger>
                            <TextInput
                                :modelValue="
                                    selectedBrand && !isBrandDropdownOpen
                                        ? selectedBrand.name
                                        : brandSearch
                                "
                                @update:modelValue="
                                    selectedBrand && !isBrandDropdownOpen
                                        ? null
                                        : (brandSearch = $event)
                                "
                                class="w-full"
                                textClass="text-sm sm:text-base"
                                :bgClass="!selectedBrand ? 'bg-gray-100' : ''"
                                placeholder="Pilih Brand"
                            >
                                <template #suffix>
                                    <button
                                        v-if="
                                            selectedBrand &&
                                            !isBrandDropdownOpen
                                        "
                                        type="button"
                                        class="absolute p-[7px] text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100 transition-all duration-300 ease-in-out"
                                        @click="
                                            selectedBrand = null;
                                            brandSearch = null;

                                            $inertia.get(
                                                route('admin.report'),
                                                {
                                                    report_type:
                                                        selectedReportType.value,
                                                }
                                            );
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            class="size-4 sm:size-5"
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
                                        isBrandDropdownOpen = false;
                                        selectedBrand = brand;
                                        brandSearch = '';

                                        $inertia.get(route('admin.report'), {
                                            report_type:
                                                selectedReportType.value,
                                            brand: brand.name,
                                        });
                                    "
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                >
                                    {{ brand.name }}
                                </li>
                            </ul>
                        </template>
                    </Dropdown>

                    <template v-if="!isStockReport">
                        <!-- Start Date -->
                        <TextInput
                            ref="startDateInput"
                            v-model="startDate"
                            type="date"
                            placeholder="Tanggal Mulai"
                            :textClass="
                                startDate
                                    ? 'text-sm sm:text-base'
                                    : '!text-gray-500 text-sm sm:text-base'
                            "
                            :disabled="isStockReport"
                            :bgClass="!startDate ? 'bg-gray-100' : ''"
                            @change="
                                $inertia.get(route('admin.report'), {
                                    report_type: selectedReportType.value,
                                    start_date: startDate
                                        ? startDate
                                        : undefined,
                                    end_date: endDate ? endDate : undefined,
                                })
                            "
                        >
                            <template #suffix>
                                <div class="absolute right-1.5">
                                    <button
                                        type="button"
                                        class="p-2"
                                        :disabled="isStockReport"
                                        @click="
                                            startDateInput.input.showPicker()
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            class="size-4 fill-gray-400"
                                        >
                                            <path
                                                d="M8 14C7.71667 14 7.47933 13.904 7.288 13.712C7.09667 13.52 7.00067 13.2827 7 13C6.99933 12.7173 7.09533 12.48 7.288 12.288C7.48067 12.096 7.718 12 8 12C8.282 12 8.51967 12.096 8.713 12.288C8.90633 12.48 9.002 12.7173 9 13C8.998 13.2827 8.902 13.5203 8.712 13.713C8.522 13.9057 8.28467 14.0013 8 14ZM12 14C11.7167 14 11.4793 13.904 11.288 13.712C11.0967 13.52 11.0007 13.2827 11 13C10.9993 12.7173 11.0953 12.48 11.288 12.288C11.4807 12.096 11.718 12 12 12C12.282 12 12.5197 12.096 12.713 12.288C12.9063 12.48 13.002 12.7173 13 13C12.998 13.2827 12.902 13.5203 12.712 13.713C12.522 13.9057 12.2847 14.0013 12 14ZM16 14C15.7167 14 15.4793 13.904 15.288 13.712C15.0967 13.52 15.0007 13.2827 15 13C14.9993 12.7173 15.0953 12.48 15.288 12.288C15.4807 12.096 15.718 12 16 12C16.282 12 16.5197 12.096 16.713 12.288C16.9063 12.48 17.002 12.7173 17 13C16.998 13.2827 16.902 13.5203 16.712 13.713C16.522 13.9057 16.2847 14.0013 16 14ZM5 22C4.45 22 3.97933 21.8043 3.588 21.413C3.19667 21.0217 3.00067 20.5507 3 20V6C3 5.45 3.196 4.97934 3.588 4.588C3.98 4.19667 4.45067 4.00067 5 4H6V3C6 2.71667 6.096 2.47934 6.288 2.288C6.48 2.09667 6.71733 2.00067 7 2C7.28267 1.99934 7.52033 2.09534 7.713 2.288C7.90567 2.48067 8.00133 2.718 8 3V4H16V3C16 2.71667 16.096 2.47934 16.288 2.288C16.48 2.09667 16.7173 2.00067 17 2C17.2827 1.99934 17.5203 2.09534 17.713 2.288C17.9057 2.48067 18.0013 2.718 18 3V4H19C19.55 4 20.021 4.196 20.413 4.588C20.805 4.98 21.0007 5.45067 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.0217 21.805 19.5507 22.0007 19 22H5ZM5 20H19V10H5V20Z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </TextInput>

                        -

                        <!-- End Date -->
                        <TextInput
                            ref="endDateInput"
                            v-model="endDate"
                            type="date"
                            placeholder="Tanggal Selesai"
                            :textClass="
                                endDate
                                    ? 'text-sm sm:text-base'
                                    : '!text-gray-500 text-sm sm:text-base'
                            "
                            :disabled="isStockReport"
                            :bgClass="!endDate ? 'bg-gray-100' : ''"
                            @change="
                                $inertia.get(route('admin.report'), {
                                    report_type: selectedReportType.value,
                                    start_date: startDate
                                        ? startDate
                                        : undefined,
                                    end_date: endDate ? endDate : undefined,
                                })
                            "
                        >
                            <template #suffix>
                                <button
                                    type="button"
                                    class="absolute p-2 right-1.5"
                                    :disabled="isStockReport"
                                    @click="endDateInput.input.showPicker()"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        class="size-4 fill-gray-400"
                                    >
                                        <path
                                            d="M8 14C7.71667 14 7.47933 13.904 7.288 13.712C7.09667 13.52 7.00067 13.2827 7 13C6.99933 12.7173 7.09533 12.48 7.288 12.288C7.48067 12.096 7.718 12 8 12C8.282 12 8.51967 12.096 8.713 12.288C8.90633 12.48 9.002 12.7173 9 13C8.998 13.2827 8.902 13.5203 8.712 13.713C8.522 13.9057 8.28467 14.0013 8 14ZM12 14C11.7167 14 11.4793 13.904 11.288 13.712C11.0967 13.52 11.0007 13.2827 11 13C10.9993 12.7173 11.0953 12.48 11.288 12.288C11.4807 12.096 11.718 12 12 12C12.282 12 12.5197 12.096 12.713 12.288C12.9063 12.48 13.002 12.7173 13 13C12.998 13.2827 12.902 13.5203 12.712 13.713C12.522 13.9057 12.2847 14.0013 12 14ZM16 14C15.7167 14 15.4793 13.904 15.288 13.712C15.0967 13.52 15.0007 13.2827 15 13C14.9993 12.7173 15.0953 12.48 15.288 12.288C15.4807 12.096 15.718 12 16 12C16.282 12 16.5197 12.096 16.713 12.288C16.9063 12.48 17.002 12.7173 17 13C16.998 13.2827 16.902 13.5203 16.712 13.713C16.522 13.9057 16.2847 14.0013 16 14ZM5 22C4.45 22 3.97933 21.8043 3.588 21.413C3.19667 21.0217 3.00067 20.5507 3 20V6C3 5.45 3.196 4.97934 3.588 4.588C3.98 4.19667 4.45067 4.00067 5 4H6V3C6 2.71667 6.096 2.47934 6.288 2.288C6.48 2.09667 6.71733 2.00067 7 2C7.28267 1.99934 7.52033 2.09534 7.713 2.288C7.90567 2.48067 8.00133 2.718 8 3V4H16V3C16 2.71667 16.096 2.47934 16.288 2.288C16.48 2.09667 16.7173 2.00067 17 2C17.2827 1.99934 17.5203 2.09534 17.713 2.288C17.9057 2.48067 18.0013 2.718 18 3V4H19C19.55 4 20.021 4.196 20.413 4.588C20.805 4.98 21.0007 5.45067 21 6V20C21 20.55 20.8043 21.021 20.413 21.413C20.0217 21.805 19.5507 22.0007 19 22H5ZM5 20H19V10H5V20Z"
                                        />
                                    </svg>
                                </button>
                            </template>
                        </TextInput>
                    </template>
                </div>
            </div>

            <div
                class="mt-6 overflow-x-auto print:!overflow-x-visible bg-gray-100 p-4 sm:p-6 print:!p-0 print:bg-transparent rounded-lg print:rounded-none h-[74vh] print:h-auto overflow-y-auto border border-1 border-gray-200 -border-offset-1 print:border-none"
            >
                <div
                    class="w-[21cm] mx-auto border border-gray-200 shadow-sm p-9 bg-white print:!bg-transparent print:!p-0 print:shadow-none print:border-none min-h-[29.7cm] print:min-h-0 scale-50 sm:scale-75 md:scale-100 print:scale-100 origin-top-left"
                >
                    <div class="mb-4 text-center">
                        <h1 class="text-lg font-bold">{{ title }}</h1>
                        <p class="text-xs">
                            Periode {{ props.start_date ?? "awal" }} sampai
                            {{ props.end_date ?? "akhir" }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <p class="text-xs">
                            Dicetak pada:
                            {{
                                props.printed_at ?? new Date().toLocaleString()
                            }}
                        </p>
                    </div>

                    <SalesReportTable
                        v-if="selectedReportType.value == 'sale'"
                        :salesReport="props.report"
                        :totals="props.totals"
                    />
                    <PurchaseReportTable
                        v-else-if="selectedReportType.value == 'purchase'"
                        :purchaseReport="props.report"
                        :totals="props.totals"
                    />
                    <StockReportTable
                        v-else-if="selectedReportType.value == 'stock'"
                        :stockReport="props.report"
                        :totals="props.totals"
                    />
                </div>
            </div>

            <LoadingDialog
                :show="printStatus === 'loading'"
                title="Memproses laporan..."
            />

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
    </AdminLayout>
</template>
