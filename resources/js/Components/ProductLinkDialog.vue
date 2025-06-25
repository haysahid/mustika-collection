<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import LinkItem from "@/Components/LinkItem.vue";

const props = defineProps({
    title: {
        type: String,
        default: "Tautan Marketplace",
    },
    links: {
        type: Array,
        default: () => [],
    },
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);

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
        <template #title>{{ props.title }}</template>
        <template #content>
            <div v-if="props.links.length > 0">
                <p class="mb-2 text-sm text-gray-700">
                    Klik tautan di bawah ini untuk lanjut berbelanja di
                    marketplace yang tersedia.
                </p>
                <div class="flex flex-col gap-2 p-1.5">
                    <a
                        v-for="link in props.links"
                        :key="link.id"
                        :href="link.url"
                        target="_blank"
                    >
                        <LinkItem
                            :name="link.name"
                            :url="link.url"
                            :icon="link.platform?.icon"
                            :index="link.id"
                        />
                    </a>
                </div>
            </div>
            <div v-else class="p-1.5 text-sm text-center text-gray-500">
                Belum ada tautan yang tersedia untuk produk ini.
            </div>
        </template>
    </DialogModal>
</template>
