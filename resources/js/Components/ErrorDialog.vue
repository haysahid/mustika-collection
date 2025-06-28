<script setup>
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close", "delete"]);

const close = () => {
    emit("close");
};
</script>
<template>
    <DialogModal
        :show="props.show"
        :showCloseButton="true"
        @close="close"
        maxWidth="sm"
    >
        <template #icon>
            <div class="flex flex-col items-center justify-center gap-3 p-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="64"
                    height="64"
                    viewBox="0 0 64 64"
                    fill="none"
                >
                    <path
                        d="M31.66 0C14.1837 0 0 14.1837 0 31.66C0 49.1363 14.1837 63.32 31.66 63.32C49.1363 63.32 63.32 49.1363 63.32 31.66C63.32 14.1837 49.1363 0 31.66 0ZM34.826 47.49H28.494V28.494H34.826V47.49ZM34.826 22.162H28.494V15.83H34.826V22.162Z"
                        fill="#FFBE1A"
                    />
                </svg>
                <slot name="content"></slot>
            </div>
        </template>
        <template #title>{{ props.title }}</template>
    </DialogModal>
</template>
