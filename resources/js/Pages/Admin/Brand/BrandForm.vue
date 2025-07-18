<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ImageInput from "@/Components/ImageInput.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";

const props = defineProps({
    brand: {
        type: Object,
        default: () => ({
            name: null,
            description: null,
            logo: null,
        }),
    },
});

const form = useForm(
    props.brand
        ? {
              ...props.brand,
              logo: props.brand.logo ? "/storage/" + props.brand.logo : null,
          }
        : {
              name: null,
              description: null,
              logo: null,
          }
);

const submit = () => {
    if (props.brand.id) {
        form.transform((data) => {
            const formData = new FormData();
            Object.keys(data).forEach((key) => {
                if (key === "logo" && !(data[key] instanceof File)) {
                    return;
                }

                if (data[key] === null || data[key] === undefined) {
                    return;
                }

                formData.append(key, data[key]);
            });
            return formData;
        }).post(
            route("admin.brand.update", {
                brand: props.brand,
            }),
            {
                onError: (errors) => {
                    openErrorDialog(errors.error);
                },
                onFinish: () => {
                    form.reset();
                },
            }
        );
        return;
    }

    form.post(route("admin.brand.store"), {
        onFinish: () => {
            form.reset();
        },
    });
};

const showErrorDialog = ref(false);
const errorMessage = ref(null);

const openErrorDialog = (message) => {
    errorMessage.value = message;
    showErrorDialog.value = true;
};
</script>

<template>
    <form @submit.prevent="submit" class="max-w-3xl">
        <div class="flex flex-col items-start gap-4">
            <!-- Name -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="name"
                    value="Nama Brand"
                    class="w-[100px] sm:w-1/5 text-lg font-bold"
                />
                <span class="hidden text-sm sm:block">:</span>
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Masukkan Nama Brand"
                    class="block w-full mt-1"
                    required
                    :autofocus="true"
                    :error="form.errors.name"
                    @update:modelValue="form.errors.name = null"
                />
            </div>

            <!-- Logo -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="logo"
                    value="Logo Brand"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden text-sm sm:block">:</span>
                <ImageInput
                    id="logo"
                    v-model="form.logo"
                    type="file"
                    accept="image/*"
                    placeholder="Upload Logo Brand"
                    class="block w-full mt-1 h-"
                    width="max-w-[180px]"
                    height="h-[120px]"
                    required
                    :error="form.errors.logo"
                    @update:modelValue="form.errors.logo = null"
                />
            </div>

            <!-- Description -->
            <div class="flex flex-col items-start w-full gap-1 sm:gap-1.5">
                <InputLabel
                    for="description"
                    value="Deskripsi Brand"
                    class="text-lg font-bold"
                />
                <TextAreaInput
                    id="description"
                    v-model="form.description"
                    type="text"
                    placeholder="Masukkan Deskripsi"
                    class="block w-full mt-1"
                    required
                    autocomplete="description"
                    :error="form.errors.description"
                    @update:modelValue="form.errors.description = null"
                />
            </div>

            <PrimaryButton type="submit" class="mt-4">
                Simpan Data
            </PrimaryButton>
        </div>

        <ErrorDialog :show="showErrorDialog" @close="showErrorDialog = false">
            <template #content>
                <div>
                    <div
                        class="mb-1 text-lg font-medium text-center text-gray-900"
                    >
                        Terjadi Kesalahan
                    </div>
                    <p class="text-center text-gray-700">{{ errorMessage }}</p>
                </div>
            </template>
        </ErrorDialog>
    </form>
</template>
