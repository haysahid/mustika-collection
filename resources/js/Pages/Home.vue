<script setup>
import { ref } from "vue";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import AdvantageCard from "@/Components/AdvantageCard.vue";
import ProductCard from "@/Components/ProductCard.vue";
import LandingSection from "@/Components/LandingSection.vue";
import JoinUs from "@/Components/JoinUs.vue";

const props = defineProps({
    store: Object,
    brands: Array,
    popularProducts: Array,
});

const certificate = ref(props.store?.certificates[0] || null);
</script>

<template>
    <LandingLayout title="Beranda">
        <!-- Banner -->
        <img
            src="/storage/promotion_banner.png"
            alt="Banner"
            class="object-cover w-full"
        />

        <!-- Brands -->
        <div
            class="bg-[#E0BEFF80] flex max-sm:flex-wrap flex-row items-center justify-center gap-4 sm:gap-8 md:gap-24 py-4 sm:py-6 px-6 lg:px-40 [&>img]:max-w-16 [&>img]:sm:max-w-20 [&>img]:lg:max-w-32 [&>img]:h-8 [&>img]:sm:h-16 [&>img]:lg:h-20 [&>img]:object-contain"
        >
            <img
                v-for="brand in props.brands || []"
                :key="brand.id"
                :src="'/storage/' + brand.logo"
                :alt="brand.name"
            />
        </div>

        <div class="p-6 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <!-- Best Seller Products -->
            <LandingSection>
                <div
                    class="flex flex-col items-start justify-center gap-4 mx-auto max-w-7xl"
                >
                    <div class="flex items-center justify-between w-full gap-4">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Produk Terlaris
                        </h1>
                        <a
                            href="#"
                            class="flex items-center justify-center gap-1 text-sm text-primary hover:underline"
                        >
                            <p>Selengkapnya</p>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-4 pt-0.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"
                                />
                            </svg>
                        </a>
                    </div>
                    <div
                        class="grid w-full grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 sm:gap-9"
                    >
                        <ProductCard
                            v-for="product in props.popularProducts || []"
                            :key="product.id"
                            :name="product.name"
                            :price="product.selling_price"
                            :image="'/storage/' + product.images[0].image"
                            :description="product.brand?.name"
                            :discount="product.discount"
                            :slug="product.slug"
                        />
                    </div>
                </div>
            </LandingSection>

            <!-- About Us -->
            <LandingSection id="about">
                <div
                    class="grid grid-cols-1 gap-8 mx-auto lg:grid-cols-2 lg:gap-14 max-w-7xl"
                >
                    <img
                        :src="'/storage/' + props.store.banner"
                        alt=""
                        class="rounded-2xl"
                    />
                    <div class="flex flex-col justify-center">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Tentang Kami
                        </h1>
                        <p class="text-gray-700">
                            {{ props.store.description }}
                        </p>
                    </div>
                </div>
            </LandingSection>

            <!-- Store Advantages -->
            <LandingSection>
                <div
                    class="grid grid-cols-1 gap-8 mx-auto lg:gap-14 xl:grid-cols-2 max-w-7xl"
                >
                    <div class="flex flex-col justify-center">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Kenapa memilih Kami?
                        </h1>
                        <p class="text-gray-700">
                            Kami memiliki keunggulan antara lain:
                        </p>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-9">
                        <AdvantageCard
                            v-for="advantage in props.store?.advantages || []"
                            :key="advantage.id"
                            :title="advantage.name"
                            :description="advantage.description"
                        />
                    </div>
                </div>
            </LandingSection>

            <!-- Certificates -->
            <LandingSection>
                <div
                    class="grid grid-cols-1 gap-8 mx-auto lg:grid-cols-2 lg:gap-14 max-w-7xl"
                >
                    <div class="flex flex-col items-start w-full gap-4">
                        <div class="flex items-center justify-center">
                            <img
                                v-if="certificate"
                                :src="'/storage/' + certificate.image"
                                :alt="certificate.name"
                                class="object-contain w-full rounded-2xl"
                            />
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                v-for="cert in props.store?.certificates || []"
                                :key="cert.id"
                                @click="certificate = cert"
                                class="w-4 h-4 bg-[#F8E4F3] rounded-full cursor-pointer transition-all duration-300 ease-in-out hover:scale-125"
                                :class="{
                                    'w-8 h-4 rounded-full bg-primary hover:scale-100 !cursor-default':
                                        cert.id === certificate.id,
                                }"
                            ></div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            {{ certificate?.name || "Sertifikat" }}
                        </h1>
                        <p class="text-gray-700">
                            {{
                                certificate?.description ||
                                "Sertifikat ini menunjukkan komitmen kami terhadap kualitas dan keamanan produk yang kami tawarkan. Kami berusaha untuk memberikan yang terbaik bagi pelanggan kami."
                            }}
                        </p>
                    </div>
                </div>
            </LandingSection>

            <!-- Join Us -->
            <LandingSection id="join">
                <JoinUs />
            </LandingSection>
        </div>
    </LandingLayout>
</template>
