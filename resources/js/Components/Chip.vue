<script setup>
import { ref } from "vue";

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    selected: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    hexCode: {
        type: String,
        default: null,
    },
    bgHexCode: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["click"]);

const onClick = () => {
    if (!props.disabled) {
        emit("click");
    }
};

const isHovered = ref(false);
</script>

<template>
    <div
        class="flex items-center justify-center gap-2 cursor-pointer px-3.5 py-2 sm:py-3 rounded-lg hover:bg-gray-50 outline outline-1 -outline-offset-1 outline-gray-400 min-w-[60px] text-center hover:outline-2 hover:-outline-offset-2 hover:outline-primary"
        :class="[
            {
                '!outline-2 !-outline-offset-2 !outline-primary':
                    props.selected,
                'opacity-30 hover:bg-transparent !cursor-default':
                    props.disabled,
            },
        ]"
        :style="
            props.bgHexCode
                ? {
                      backgroundColor: props.bgHexCode,
                      outlineColor: props.selected
                          ? `${props.hexCode} !important`
                          : isHovered
                          ? `${props.hexCode}40`
                          : props.bgHexCode,
                  }
                : {}
        "
        @click="onClick"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <slot name="prefix" />
        <p :style="props.hexCode ? { color: props.hexCode } : {}">
            {{ props.label }}
        </p>
        <slot name="suffix" />
    </div>
</template>
