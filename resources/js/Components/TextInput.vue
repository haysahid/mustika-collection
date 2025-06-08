<script setup>
import { onMounted, ref } from "vue";
import { useSlots } from "vue";

defineProps({
    id: {
        type: String,
        default: null,
    },
    name: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: "text",
    },
    placeholder: {
        type: String,
        default: "Masukkan teks...",
    },
    autofocus: {
        type: Boolean,
        default: false,
    },
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

const slots = useSlots();
const hasPrefix = !!slots.prefix;

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <label :for="id" class="relative border-none p-0 flex items-center">
        <slot name="prefix"></slot>
        <input
            ref="input"
            :id="id"
            :name="name"
            :placeholder="placeholder"
            :type="type"
            :autofocus="autofocus"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-full shadow-sm w-full"
            :class="{
                'pl-11': hasPrefix,
            }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </label>
</template>
