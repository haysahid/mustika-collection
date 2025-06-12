<script setup>
import { useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    store: {
        type: Object,
        required: null,
    },
});

const form = useForm(props.store);

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
    <AdminLayout title="Informasi Toko" :showTitle="true">
        <template #icon>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="27"
                viewBox="0 0 26 27"
                class="fill-primary"
            >
                <rect
                    opacity="0.01"
                    x="26"
                    y="26.6953"
                    width="26"
                    height="26"
                    transform="rotate(180 26 26.6953)"
                />
                <path
                    d="M3.51 8.59243L12.74 13.6083C12.9025 13.6949 13.0975 13.6949 13.26 13.6083L22.49 8.59243C22.6622 8.5074 22.7665 8.32734 22.7547 8.13567C22.7429 7.94399 22.6172 7.77814 22.4358 7.71493L13.2058 3.94493C13.074 3.89075 12.926 3.89075 12.7942 3.94493L3.56416 7.71493C3.38282 7.77814 3.25713 7.94399 3.24529 8.13567C3.23346 8.32734 3.33781 8.5074 3.51 8.59243Z"
                />
                <path
                    d="M22.4359 12.2434L20.4534 11.3984L13.26 15.3093C13.0975 15.3959 12.9025 15.3959 12.74 15.3093L5.54669 11.3984L3.56419 12.2434C3.39307 12.3333 3.28589 12.5106 3.28589 12.7039C3.28589 12.8971 3.39307 13.0744 3.56419 13.1643L12.7942 18.4726C12.9538 18.5702 13.1546 18.5702 13.3142 18.4726L22.5442 13.1643C22.7072 13.0558 22.7954 12.865 22.7725 12.6706C22.7497 12.4762 22.6195 12.3111 22.4359 12.2434Z"
                />
                <path
                    d="M22.4359 17.054L20.7459 16.3174L13.26 20.3907C13.0975 20.4774 12.9025 20.4774 12.74 20.3907L5.25419 16.3174L3.56419 17.054C3.38819 17.1453 3.27771 17.327 3.27771 17.5253C3.27771 17.7236 3.38819 17.9053 3.56419 17.9965L12.7942 23.4132C12.9567 23.4999 13.1517 23.4999 13.3142 23.4132L22.5442 17.9965C22.7121 17.8866 22.8036 17.6913 22.7807 17.4919C22.7578 17.2925 22.6243 17.1231 22.4359 17.054Z"
                />
            </svg>
        </template>

        <div class="md:px-11">
            <form action="" class="max-w-3xl">
                <div class="flex flex-col items-start gap-4">
                    <!-- Name -->
                    <div
                        class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
                    >
                        <InputLabel
                            for="name"
                            value="Nama Toko"
                            class="w-[100px] sm:w-1/5 text-lg font-bold"
                        />
                        <span class="hidden text-sm sm:block">:</span>
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Masukkan Nama Toko"
                            class="block w-full mt-1"
                            required
                            :autofocus="true"
                            autocomplete="name"
                            :error="form.errors.username"
                            @update:modelValue="form.errors.username = null"
                        />
                    </div>

                    <!-- Phone -->
                    <div
                        class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
                    >
                        <InputLabel
                            for="phone"
                            value="No. WhatsApp"
                            class="w-[100px] sm:w-1/5 text-lg font-bold"
                        />
                        <span class="hidden text-sm sm:block">:</span>
                        <TextInput
                            id="phone"
                            v-model="form.phone"
                            type="text"
                            placeholder="Masukkan No. WhatsApp"
                            class="block w-full mt-1"
                            required
                            autocomplete="phone"
                            :error="form.errors.phone"
                            @update:modelValue="form.errors.phone = null"
                        />
                    </div>

                    <!-- Email -->
                    <div
                        class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
                    >
                        <InputLabel
                            for="email"
                            value="Email"
                            class="w-[100px] sm:w-1/5 text-lg font-bold"
                        />
                        <span class="hidden text-sm sm:block">:</span>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Masukkan Email"
                            class="block w-full mt-1"
                            required
                            autocomplete="email"
                            :error="form.errors.email"
                            @update:modelValue="form.errors.email = null"
                        />
                    </div>

                    <!-- Address -->
                    <div
                        class="flex flex-col w-full gap-y-1 gap-x-4 sm:items-center sm:flex-row"
                    >
                        <InputLabel
                            for="address"
                            value="Alamat Toko"
                            class="w-[100px] sm:w-1/5 text-lg font-bold"
                        />
                        <span class="hidden text-sm sm:block">:</span>
                        <TextInput
                            id="address"
                            v-model="form.address"
                            type="text"
                            placeholder="Masukkan Alamat Toko"
                            class="block w-full mt-1"
                            required
                            autocomplete="address"
                            :error="form.errors.address"
                            @update:modelValue="form.errors.address = null"
                        />
                    </div>

                    <!-- Advantages -->
                    <div class="w-full">
                        <InputLabel
                            value="Keunggulan Toko"
                            class="mb-2.5 sm:mb-4"
                        />
                        <div
                            class="grid grid-cols-1 p-4 gap-x-6 gap-y-4 rounded-2xl sm:grid-cols-2 border-dashed-default"
                        >
                            <div
                                v-for="(advantage, index) in form.advantages"
                                :key="index"
                                class="flex flex-col items-start gap-2"
                            >
                                <InputLabel
                                    :for="'advantage_name_' + index"
                                    :value="'Keunggulan ' + (index + 1)"
                                    class="text-lg font-bold"
                                />
                                <div class="flex flex-col w-full gap-2">
                                    <TextInput
                                        :id="'advantage_name_' + index"
                                        v-model="advantage.name"
                                        type="text"
                                        placeholder="Masukkan Nama Keunggulan"
                                        class="block w-full mt-1"
                                        required
                                        autocomplete="advantage_name"
                                        :error="
                                            form.errors.advantages?.[index]
                                                ?.name
                                        "
                                    />
                                    <TextAreaInput
                                        :id="'advantage_description_' + index"
                                        v-model="advantage.description"
                                        type="text"
                                        placeholder="Masukkan Deskripsi Keunggulan"
                                        height="sm:max-h-[110px]"
                                        class="block w-full mt-1"
                                        required
                                        autocomplete="advantage_description"
                                        :error="
                                            form.errors.advantages?.[index]
                                                ?.description
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div
                        class="flex flex-col items-start w-full gap-1 sm:gap-1.5"
                    >
                        <InputLabel
                            for="description"
                            value="Deskripsi Toko"
                            class="text-lg font-bold"
                        />
                        <TextAreaInput
                            id="description"
                            v-model="form.description"
                            type="text"
                            placeholder="Masukkan Deskripsi Toko"
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
        </div>
    </AdminLayout>
</template>
