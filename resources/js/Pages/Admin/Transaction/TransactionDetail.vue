<script setup lang="ts">
import { ref, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import OrderDetail from "@/Pages/Order/OrderDetail.vue";
import ChangeTransactionStatusDialog from "./ChangeTransactionStatusDialog.vue";
import axios from "axios";
import OrderContentRow from "@/Components/OrderContentRow.vue";

const props = defineProps({
    invoice: {
        type: Object as () => InvoiceEntity,
        required: true,
    },
    transaction: {
        type: Object as () => TransactionEntity,
        required: true,
    },
    items: {
        type: Array as () => TransactionItemEntity[],
        required: true,
    },
});

const showChangeStatusDialog = ref(false);

function changeStatus(newStatus: string) {
    const token = `Bearer ${localStorage.getItem("access_token")}`;

    axios
        .put(
            "/api/admin/change-order-status",
            {
                transaction_id: props.transaction.id,
                status: newStatus,
            },
            {
                headers: {
                    Authorization: token,
                },
            }
        )
        .then(() => {
            window.location.reload();
        })
        .catch((error) => {
            console.error("Error changing transaction status:", error);
        });
}

window.onpopstate = function () {
    location.reload();
};
</script>

<template>
    <AdminLayout :title="`#${props.transaction.code}`" :showTitle="true">
        <template #icon>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="27"
                viewBox="0 0 26 27"
                class="fill-primary"
            >
                <path
                    d="M13 2.86133C14.1492 2.86133 15.2514 3.31787 16.0641 4.13053C16.8767 4.94319 17.3333 6.04539 17.3333 7.19466H19.5379C20.0992 7.19459 20.6387 7.4124 21.0427 7.80221C21.4466 8.19202 21.6835 8.7234 21.7035 9.28441L22.1671 22.2844C22.1774 22.5752 22.1291 22.865 22.025 23.1367C21.9209 23.4084 21.7631 23.6563 21.5611 23.8657C21.3591 24.075 21.117 24.2416 20.8493 24.3554C20.5815 24.4692 20.2936 24.5279 20.0026 24.528H5.99729C5.70635 24.5279 5.41841 24.4692 5.15065 24.3554C4.88289 24.2416 4.64079 24.075 4.43879 23.8657C4.23679 23.6563 4.07902 23.4084 3.97491 23.1367C3.87079 22.865 3.82246 22.5752 3.83279 22.2844L4.29645 9.28441C4.31638 8.7234 4.55328 8.19202 4.95723 7.80221C5.36119 7.4124 5.90067 7.19459 6.46204 7.19466H8.66662C8.66662 6.04539 9.12317 4.94319 9.93582 4.13053C10.7485 3.31787 11.8507 2.86133 13 2.86133ZM10.8333 9.36133H8.66662V10.4447C8.66693 10.7208 8.77266 10.9864 8.96221 11.1871C9.15176 11.3879 9.41082 11.5087 9.68646 11.5249C9.96211 11.5411 10.2335 11.4514 10.4453 11.2742C10.657 11.097 10.7931 10.8456 10.8257 10.5714L10.8333 10.4447V9.36133ZM17.3333 9.36133H15.1666V10.4447C15.1666 10.732 15.2808 11.0075 15.4839 11.2107C15.6871 11.4139 15.9626 11.528 16.25 11.528C16.5373 11.528 16.8128 11.4139 17.016 11.2107C17.2192 11.0075 17.3333 10.732 17.3333 10.4447V9.36133ZM13 5.02799C12.4533 5.02782 11.9268 5.23427 11.526 5.60595C11.1252 5.97763 10.8797 6.48708 10.8387 7.03216L10.8333 7.19466H15.1666C15.1666 6.62003 14.9383 6.06893 14.532 5.6626C14.1257 5.25627 13.5746 5.02799 13 5.02799Z"
                />
            </svg>
        </template>

        <OrderDetail
            :invoice="props.invoice"
            :transaction="props.transaction"
            :items="props.items"
            class="!px-0 !pt-8 md:!px-11"
        >
            <template #actions>
                <PrimaryButton
                    @click="showChangeStatusDialog = true"
                    class="w-full"
                >
                    Ubah Status
                </PrimaryButton>
            </template>
        </OrderDetail>

        <ChangeTransactionStatusDialog
            :show="showChangeStatusDialog"
            :currentStatus="props.transaction.status"
            :options="[
                {
                    value: 'pending',
                    label: 'PENDING',
                    disabled: false,
                },
                {
                    value: 'paid',
                    label: 'PAID',
                    disabled: false,
                },
                {
                    value: 'processing',
                    label: 'PROCESSING',
                    disabled: false,
                },
                {
                    value: 'completed',
                    label: 'COMPLETED',
                    disabled: false,
                },
                {
                    value: 'cancelled',
                    label: 'CANCELLED',
                    disabled: false,
                },
            ]"
            @change="
                showChangeStatusDialog = false;
                if ($event != props.transaction.status) {
                    changeStatus($event);
                }
            "
            @close="showChangeStatusDialog = false"
        />
    </AdminLayout>
</template>
