<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    showCloseButton: {
        type: Boolean,
        default: false,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="relative">
            <button
                v-if="showCloseButton"
                type="button"
                class="absolute p-2 text-gray-400 bg-white rounded-full top-1 right-1 hover:bg-gray-100"
                @click="close"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
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
            <div class="flex flex-col items-center p-6">
                <div class="mb-2 text-lg font-medium text-center text-gray-900">
                    <slot name="title" />
                </div>

                <slot name="content" />

                <div class="flex flex-row justify-center mt-2 text-end">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </Modal>
</template>
