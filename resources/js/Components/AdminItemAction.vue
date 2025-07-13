<script setup>
import { computed, getCurrentInstance } from "vue";

defineEmits(["edit", "delete"]);

const hasEditCallback = computed(() => {
    return !!getCurrentInstance()?.vnode?.props?.["onEdit"];
});

const hasDeleteCallback = computed(() => {
    return !!getCurrentInstance()?.vnode?.props?.["onDelete"];
});
</script>

<template>
    <div class="flex justify-center gap-2">
        <button
            v-if="hasEditCallback"
            type="button"
            class="p-2 bg-blue-500 rounded-md hover:bg-blue-600"
            @click="$emit('edit')"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                class="stroke-white size-4"
            >
                <path
                    d="M11 5.00016H6C5.46957 5.00016 4.96086 5.21088 4.58579 5.58595C4.21071 5.96102 4 6.46973 4 7.00016V18.0002C4 18.5306 4.21071 19.0393 4.58579 19.4144C4.96086 19.7894 5.46957 20.0002 6 20.0002H17C17.5304 20.0002 18.0391 19.7894 18.4142 19.4144C18.7893 19.0393 19 18.5306 19 18.0002V13.0002M17.586 3.58616C17.7705 3.39514 17.9912 3.24278 18.2352 3.13796C18.4792 3.03314 18.7416 2.97797 19.0072 2.97566C19.2728 2.97335 19.5361 3.02396 19.7819 3.12452C20.0277 3.22508 20.251 3.37359 20.4388 3.56137C20.6266 3.74916 20.7751 3.97246 20.8756 4.21825C20.9762 4.46405 21.0268 4.72741 21.0245 4.99296C21.0222 5.25852 20.967 5.52096 20.8622 5.76497C20.7574 6.00898 20.605 6.22967 20.414 6.41416L11.828 15.0002H9V12.1722L17.586 3.58616Z"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>
        <button
            v-if="hasDeleteCallback"
            type="button"
            class="p-2 bg-red-500 rounded-md hover:bg-red-600"
            @click="$emit('delete')"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                class="stroke-white size-4"
            >
                <path
                    d="M3 6H21M19 6V20C19 21 18 22 17 22H7C6 22 5 21 5 20V6M8 6V4C8 3 9 2 10 2H14C15 2 16 3 16 4V6M10 11V17M14 11V17"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>
    </div>
</template>
