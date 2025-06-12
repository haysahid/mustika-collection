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
    rows: {
        type: Number,
        default: 4,
    },
    height: {
        type: String,
        default: "max-h-[300px]",
    },
    error: {
        type: String,
        default: null,
    },
    modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

const slots = useSlots();
const hasPrefix = !!slots.prefix;

function updateValue(value) {
    emit("update:modelValue", value);

    input.value.parentNode.dataset.clonedVal = value;
}

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }

    input.value.parentNode.dataset.clonedVal = props.modelValue;
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div>
        <label
            :for="id"
            class="relative grid after:px-3.5 after:py-2 [&>textarea]:text-inherit after:text-inherit [&>textarea]:resize-none [&>textarea]:overflow-auto [&>textarea]:[grid-area:1/1/2/2] after:[grid-area:1/1/2/2] after:whitespace-pre-wrap after:invisible after:content-[attr(data-cloned-val)_'_'] after:border max-h-[300px]"
            :class="[props.height]"
        >
            <slot name="prefix"></slot>
            <textarea
                ref="input"
                :id="props.id"
                :name="props.name"
                :placeholder="props.placeholder"
                :type="props.type"
                :autofocus="props.autofocus ? true : false"
                :autocomplete="props.autocomplete"
                :rows="props.rows"
                class="w-full px-3.5 py-2 border-gray-300 shadow-sm rounded-2xl focus:border-indigo-500 focus:ring-indigo-500 max-h-[300px]"
                :class="[
                    {
                        'pl-11': hasPrefix,
                        'border-red-500 focus:border-red-500 focus:ring-red-500':
                            props.error,
                    },
                    props.height,
                ]"
                :value="props.modelValue"
                @input="updateValue($event.target.value)"
            />
        </label>
        <InputError class="mt-1" :message="props.error" />
    </div>
</template>
