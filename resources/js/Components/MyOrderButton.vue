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
                d="M11.9999 2C13.0607 2 14.0782 2.42143 14.8283 3.17157C15.5785 3.92172 15.9999 4.93913 15.9999 6H18.0349C18.5531 5.99993 19.0511 6.20099 19.4239 6.56081C19.7968 6.92064 20.0155 7.41114 20.0339 7.929L20.4619 19.929C20.4714 20.1974 20.4268 20.4649 20.3307 20.7157C20.2346 20.9665 20.089 21.1953 19.9025 21.3886C19.716 21.5819 19.4926 21.7356 19.2454 21.8407C18.9982 21.9457 18.7324 21.9999 18.4639 22H5.53588C5.26732 21.9999 5.00154 21.9457 4.75437 21.8407C4.50721 21.7356 4.28373 21.5819 4.09727 21.3886C3.91081 21.1953 3.76518 20.9665 3.66907 20.7157C3.57296 20.4649 3.52835 20.1974 3.53788 19.929L3.96588 7.929C3.98428 7.41114 4.20295 6.92064 4.57583 6.56081C4.94871 6.20099 5.4467 5.99993 5.96488 6H7.99988C7.99988 4.93913 8.42131 3.92172 9.17145 3.17157C9.9216 2.42143 10.939 2 11.9999 2ZM7.99988 8H5.96488L5.53588 20H18.4639L18.0349 8H15.9999V9C15.9996 9.25488 15.902 9.50003 15.727 9.68537C15.5521 9.8707 15.3129 9.98224 15.0585 9.99717C14.804 10.0121 14.5535 9.92933 14.358 9.76574C14.1626 9.60215 14.037 9.3701 14.0069 9.117L13.9999 9V8H9.99988V9C9.9996 9.25488 9.902 9.50003 9.72703 9.68537C9.55206 9.8707 9.31293 9.98224 9.05849 9.99717C8.80405 10.0121 8.5535 9.92933 8.35805 9.76574C8.1626 9.60215 8.03699 9.3701 8.00688 9.117L7.99988 9V8ZM11.9999 4C11.4953 3.99984 11.0093 4.19041 10.6393 4.5335C10.2694 4.87659 10.0427 5.34684 10.0049 5.85L9.99988 6H13.9999C13.9999 5.46957 13.7892 4.96086 13.4141 4.58579C13.039 4.21071 12.5303 4 11.9999 4Z"
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
