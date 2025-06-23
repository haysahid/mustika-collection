<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const store = page.props.store;
const phone = store.phone?.replace(/[^0-9]/g, "");
</script>

<template>
    <div
        class="w-full max-w-7xl mx-auto rounded-xl bg-[url(/storage/join_banner.png)] bg-cover bg-center bg-no-repeat h-[612px] flex items-center justify-center"
    >
        <div
            class="text-center max-w-[400px] flex flex-col items-center gap-6 px-4"
        >
            <h1 class="text-4xl font-bold sm:text-5xl">
                Dapatkan Banyak Diskon!
            </h1>
            <p class="text-gray-700">
                Bergabunglah dengan komunitas kami dan nikmati berbagai
                keuntungan sebagai anggota. Dapatkan akses eksklusif ke produk
                terbaru, diskon khusus, dan banyak lagi!
            </p>
            <div class="flex flex-col items-center justify-center gap-4">
                <a
                    :href="`https://wa.me/${phone}?text=Halo, saya ingin bergabung sebagai agen.`"
                    target="_blank"
                    class="px-6 py-3 font-semibold text-white transition duration-300 rounded-md bg-primary hover:bg-primary/90"
                >
                    Gabung Agen
                </a>
                <a
                    :href="`https://wa.me/${phone}?text=Halo, saya ingin bergabung sebagai reseller.`"
                    target="_blank"
                    class="bg-[#E4CFF6] text-primary px-6 py-3 rounded-md hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                >
                    Gabung Reseller
                </a>
            </div>
        </div>
    </div>
</template>
