<script setup lang="ts">
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, computed } from "vue";

const props = defineProps({
    unit: {
        type: String,
        default: null,
    },
    max: {
        type: Number,
        default: null,
    },
    modelValue: {
        type: Number,
        default: 1,
    },
    label: {
        type: String,
        default: "Jumlah",
    },
    showAvailability: {
        type: Boolean,
        default: true,
    },
    bgClass: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

function updateValue(value: number) {
    emit("update:modelValue", Math.max(1, value));
}
</script>

<template>
    <div class="flex gap-4">
        <InputLabel
            v-if="label"
            for="quantity"
            :value="label"
            class="!text-gray-500 min-w-[80px] py-3 h-min !text-base"
        />
        <div class="flex items-center gap-4">
            <TextInput
                id="quantity"
                :modelValue="modelValue"
                type="number"
                min="1"
                :hide-arrows="true"
                class="w-[120px] sm:w-[140px] grow-0"
                :bgClass="
                    'rounded-lg bg-white px-3.5 py-2 sm:py-3 border-gray-400 ' +
                    bgClass
                "
                textClass="text-center"
                placeholder="0"
                errorClass="px-0"
                :error="
                    !modelValue || (props.max && modelValue <= 0)
                        ? 'Jumlah tidak valid'
                        : props.max && modelValue > props.max
                        ? 'Stok tidak cukup'
                        : ''
                "
                @input="updateValue($event.target.valueAsNumber)"
            >
                <template #prefix>
                    <button
                        class="absolute p-1 text-gray-600 left-2 hover:text-gray-800 disabled:opacity-30"
                        @click="updateValue(modelValue - 1)"
                        :disabled="modelValue <= 1"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="17"
                            viewBox="0 0 16 17"
                            fill="none"
                        >
                            <path
                                d="M12 8.69482H4"
                                stroke="black"
                                stroke-width="1.2381"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </template>
                <template #suffix>
                    <button
                        class="absolute p-1 text-gray-600 right-2 size-6 hover:text-gray-800 disabled:opacity-30"
                        @click="updateValue(modelValue + 1)"
                        :disabled="props.max && modelValue >= props.max"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="17"
                            viewBox="0 0 16 17"
                            fill="none"
                        >
                            <path
                                d="M13.3334 8.69491H2.66669M8.00002 3.36157V14.0282V3.36157Z"
                                stroke="black"
                                stroke-width="1.2381"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </template>
            </TextInput>

            <p v-if="props.max && props.showAvailability" class="text-gray-600">
                Tersedia {{ props.max }}
                {{ props.unit }}
            </p>
        </div>
    </div>
</template>
