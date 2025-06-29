<script setup>
const props = defineProps({
    length: {
        type: Number,
        default: 0,
    },
    showBackground: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["click"]);
</script>

<template>
    <button
        type="button"
        class="relative flex items-center justify-center p-2 transition duration-150 ease-in-out rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:hover:bg-white/20 focus:text-white group"
        :class="{
            '!bg-transparent !p-0 hover:!bg-transparent': !showBackground,
        }"
        @click="emit('click')"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="25"
            viewBox="0 0 24 25"
            class="fill-white/80 group-hover:fill-white"
            :class="{
                'group-hover:fill-white/80': !showBackground,
            }"
        >
            <path
                d="M15 4H6V20H18V7H15V4ZM6 2H16L20 6V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V4C4 3.46957 4.21071 2.96086 4.58579 2.58579C4.96086 2.21071 5.46957 2 6 2ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"
            />
        </svg>
        <div
            v-if="length > 0"
            class="px-1 py-0.5 ms-2 text-xs font-semibold text-white bg-[#CE4DB1] rounded-full min-w-[20px] text-center absolute top-0 right-0"
        >
            {{ length }}
        </div>
    </button>
</template>
