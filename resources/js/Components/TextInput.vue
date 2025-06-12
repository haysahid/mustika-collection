<script setup>
import { onMounted, ref } from "vue";
import { useSlots } from "vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
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
    autocomplete: {
        type: String,
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: null,
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

const slots = useSlots();
const hasPrefix = !!slots.prefix;

function updateValue(value) {
    emit("update:modelValue", value);
}

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div>
        <label :for="id" class="relative flex items-center p-0 border-none">
            <slot name="prefix"></slot>
            <input
                ref="input"
                :id="props.id"
                :name="props.name"
                :placeholder="props.placeholder"
                :type="props.type"
                :autofocus="props.autofocus ? true : false"
                :autocomplete="props.autocomplete"
                :required="props.required"
                :readonly="props.readonly"
                class="w-full px-3.5 border-gray-300 rounded-full shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{
                    'pl-11': hasPrefix,
                    'border-red-500 focus:border-red-500 focus:ring-red-500':
                        props.error,
                }"
                :value="props.modelValue"
                @input="updateValue($event.target.value)"
            />
            <slot name="suffix"></slot>
        </label>
        <InputError class="mt-1" :message="props.error" />
    </div>
</template>
