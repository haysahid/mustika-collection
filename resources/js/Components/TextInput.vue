<script setup>
import { onMounted, ref, onUpdated } from "vue";
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
        default: null,
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
    containerClass: {
        type: String,
        default: "",
    },
    bgClass: {
        type: String,
        default: "",
    },
    textClass: {
        type: String,
        default: "",
    },
    errorClass: {
        type: String,
        default: "",
    },
    hideArrows: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

const slots = useSlots();
const hasPrefix = ref(!!slots.prefix);
const hasSuffix = ref(!!slots.suffix);

function updateValue(value) {
    emit("update:modelValue", value);
}

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

onUpdated(() => {
    hasPrefix.value = !!slots.prefix;
    hasSuffix.value = !!slots.suffix;
});

defineExpose({
    focus: () => input.value.focus(),
    input,
});
</script>

<template>
    <div>
        <label
            :for="id"
            class="relative flex items-center p-0 border-none"
            :class="containerClass"
        >
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
                :disabled="props.disabled"
                class="w-full px-4 py-2 border-gray-300 rounded-full shadow-sm focus:border-indigo-500 focus:ring-indigo-500 overflow-ellipsis"
                :class="[
                    {
                        'pl-11': hasPrefix,
                        'pr-11': hasSuffix,
                        'border-red-500 focus:border-red-500 focus:ring-red-500':
                            props.error,
                    },
                    {
                        'no-arrows':
                            props.hideArrows && props.type === 'number',
                    },

                    props.bgClass,
                    props.textClass,
                ]"
                :value="props.modelValue"
                @input="updateValue($event.target.value)"
            />
            <slot name="suffix"></slot>
        </label>
        <InputError
            :message="props.error"
            class="px-4 mt-1"
            :class="[props.errorClass]"
        />
    </div>
</template>
