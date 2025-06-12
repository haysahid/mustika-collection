<script setup lang="ts">
interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

const props = defineProps({
    links: {
        type: Array as () => Link[],
        default: () => [] as Link[],
    },
});
</script>

<template>
    <div class="flex justify-start mt-6">
        <nav
            class="flex items-center gap-1.5 sm:gap-2 p-1.5 sm:p-2.5 text-primary bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.05)]"
        >
            <template v-for="(link, index) in props.links" :key="index">
                <a
                    v-if="link.label.includes('Previous')"
                    :href="link.active ? null : link.url"
                    class="size-6 sm:size-10 aspect-square flex items-center justify-center text-primary rounded-md sm:rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                    :class="{
                        'hover:bg-transparent': !link.url,
                    }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        class="fill-gray-500"
                        :class="{
                            'fill-gray-300': !link.url,
                        }"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M11.7803 5.21967C12.0732 5.51256 12.0732 5.98744 11.7803 6.28033L8.06066 10L11.7803 13.7197C12.0732 14.0126 12.0732 14.4874 11.7803 14.7803C11.4874 15.0732 11.0126 15.0732 10.7197 14.7803L6.46967 10.5303C6.17678 10.2374 6.17678 9.76256 6.46967 9.46967L10.7197 5.21967C11.0126 4.92678 11.4874 4.92678 11.7803 5.21967Z"
                        />
                    </svg>
                </a>
                <a
                    v-if="
                        !link.label.includes('Next') &&
                        !link.label.includes('Previous')
                    "
                    :href="link.active ? null : link.url"
                    :class="[
                        'size-6 sm:size-10 aspect-square flex items-center justify-center rounded-lg transition duration-300 font-semibold',
                        link.active
                            ? 'bg-yellow-500 text-white hover:bg-yellow-500/80 active:bg-yellow-500/90 focus:bg-yellow-500 focus:ring-yellow-500'
                            : 'text-primary hover:bg-[#E4CFF6]/80',
                    ]"
                >
                    <span v-html="link.label" />
                </a>
                <a
                    v-if="link.label.includes('Next')"
                    :href="link.active ? null : link.url"
                    class="size-6 sm:size-10 aspect-square flex items-center justify-center text-primary rounded-md sm:rounded-lg hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                    :class="{
                        'hover:bg-transparent': !link.url,
                    }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        class="fill-gray-500"
                        :class="{
                            'fill-gray-300': !link.url,
                        }"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8.21967 5.21967C8.51256 4.92678 8.98744 4.92678 9.28033 5.21967L13.5303 9.46967C13.8232 9.76256 13.8232 10.2374 13.5303 10.5303L9.28033 14.7803C8.98744 15.0732 8.51256 15.0732 8.21967 14.7803C7.92678 14.4874 7.92678 14.0126 8.21967 13.7197L11.9393 10L8.21967 6.28033C7.92678 5.98744 7.92678 5.51256 8.21967 5.21967Z"
                        />
                    </svg>
                </a>
            </template>
        </nav>
    </div>
</template>
