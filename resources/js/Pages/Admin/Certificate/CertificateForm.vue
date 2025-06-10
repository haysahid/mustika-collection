<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ImageInput from "@/Components/ImageInput.vue";

const form = useForm({
    name: "",
    description: "",
    image: null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("admin.login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <form action="" class="max-w-3xl">
        <div class="flex flex-col items-start gap-4">
            <!-- Name -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
            >
                <InputLabel
                    for="name"
                    value="Nama Sertifikat"
                    class="w-[100px] sm:w-1/5 text-lg font-bold"
                />
                <span class="hidden sm:block">:</span>
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Masukkan Nama Sertifikat"
                    class="block w-full mt-1"
                    required
                    :autofocus="true"
                    :error="form.errors.username"
                    @update:modelValue="form.errors.username = null"
                />
            </div>

            <!-- Image -->
            <div
                class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-start sm:flex-row"
            >
                <InputLabel
                    for="image"
                    value="Gambar Sertifikat"
                    class="text-lg font-bold sm:w-1/5"
                />
                <span class="hidden sm:block">:</span>
                <ImageInput
                    id="image"
                    v-model="form.image"
                    type="file"
                    accept="image/*"
                    placeholder="Upload Sertifikat"
                    class="block w-full mt-1 h-"
                    width="max-w-[180px]"
                    height="h-[120px]"
                    required
                    :error="form.errors.image"
                    @update:modelValue="form.errors.image = null"
                />
            </div>

            <!-- Description -->
            <div class="flex flex-col items-start w-full gap-1 sm:gap-1.5">
                <InputLabel
                    for="description"
                    value="Deskripsi Sertifikat"
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

            <PrimaryButton class="mt-4" @click="submit">
                Simpan Data
            </PrimaryButton>
        </div>
    </form>
</template>
