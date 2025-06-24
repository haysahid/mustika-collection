<script setup>
import { ref } from "vue";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import AdvantageCard from "@/Components/AdvantageCard.vue";
import ProductCard from "@/Components/ProductCard.vue";
import LandingSection from "@/Components/LandingSection.vue";
import JoinUs from "@/Components/JoinUs.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link } from "@inertiajs/vue3";
import AOS from "aos";
AOS.init();

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
                data-aos="zoom-in"
                data-aos-duration="600"
                data-aos-delay="100"
                data-aos-once="true"
            />
        </div>

        <div class="p-6 sm:p-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <!-- Best Seller Products -->
            <LandingSection>
                <div
                    data-aos="fade-up"
                    data-aos-duration="600"
                    data-aos-delay="200"
                    data-aos-once="true"
                    class="flex flex-col items-start justify-center gap-4 mx-auto max-w-7xl"
                >
                    <div class="flex flex-col items-start justify-center gap-4">
                        <div
                            class="flex items-center justify-between w-full gap-12 mb-4"
                        >
                            <h1
                                class="text-2xl font-bold sm:text-3xl text-nowrap"
                            >
                                Produk Terlaris
                            </h1>
                            <TextInput
                                placeholder="Cari produk..."
                                bgClass="bg-gray-100"
                                textClass="text-sm sm:text-base !ps-4"
                                class="relative w-full max-w-md"
                                @keyup.enter="
                                    (e) => {
                                        $inertia.get(route('catalog'), {
                                            search: e.target.value,
                                        });
                                    }
                                "
                            >
                                <template #suffix>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        class="absolute -translate-y-1/2 fill-gray-400 right-3 top-1/2 size-5"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M11 17C11.7879 17 12.5681 16.8448 13.2961 16.5433C14.0241 16.2417 14.6855 15.7998 15.2426 15.2426C15.7998 14.6855 16.2417 14.0241 16.5433 13.2961C16.8448 12.5681 17 11.7879 17 11C17 10.2121 16.8448 9.43185 16.5433 8.7039C16.2417 7.97595 15.7998 7.31451 15.2426 6.75736C14.6855 6.20021 14.0241 5.75825 13.2961 5.45672C12.5681 5.15519 11.7879 5 11 5C9.4087 5 7.88258 5.63214 6.75736 6.75736C5.63214 7.88258 5 9.4087 5 11C5 12.5913 5.63214 14.1174 6.75736 15.2426C7.88258 16.3679 9.4087 17 11 17ZM11 19C13.1217 19 15.1566 18.1571 16.6569 16.6569C18.1571 15.1566 19 13.1217 19 11C19 8.87827 18.1571 6.84344 16.6569 5.34315C15.1566 3.84285 13.1217 3 11 3C8.87827 3 6.84344 3.84285 5.34315 5.34315C3.84285 6.84344 3 8.87827 3 11C3 13.1217 3.84285 15.1566 5.34315 16.6569C6.84344 18.1571 8.87827 19 11 19Z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M15.3201 15.2903C15.5082 15.1035 15.7629 14.9991 16.0281 15C16.2933 15.0009 16.5472 15.1072 16.7341 15.2953L20.7091 19.2953C20.8908 19.4844 20.9909 19.7373 20.9879 19.9995C20.9849 20.2618 20.879 20.5123 20.6931 20.6972C20.5071 20.8822 20.256 20.9866 19.9937 20.9881C19.7315 20.9896 19.4791 20.8881 19.2911 20.7053L15.3161 16.7053C15.1291 16.5172 15.0245 16.2626 15.0253 15.9975C15.026 15.7323 15.1321 15.4783 15.3201 15.2913V15.2903Z"
                                        />
                                    </svg>
                                </template>
                            </TextInput>
                        </div>
                        <div
                            class="grid w-full grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 sm:gap-9"
                        >
                            <ProductCard
                                v-for="product in props.popularProducts || []"
                                :key="product.id"
                                :name="product.name"
                                :price="product.selling_price"
                                :image="
                                    product.images && product.images.length > 0
                                        ? '/storage/' + product.images[0].image
                                        : null
                                "
                                :description="product.brand?.name"
                                :discount="product.discount"
                                :slug="product.slug"
                            />
                        </div>
                    </div>

                    <Link :href="route('catalog')" class="mx-auto mt-6">
                        <PrimaryButton> Lihat Semua Produk </PrimaryButton>
                    </Link>
                </div>
            </LandingSection>

            <!-- About Us -->
            <LandingSection id="about">
                <div
                    class="grid grid-cols-1 gap-8 mx-auto lg:grid-cols-2 lg:gap-14 max-w-7xl"
                >
                    <img
                        data-aos="fade-up"
                        data-aos-duration="600"
                        :src="'/storage/' + props.store.banner"
                        alt=""
                        class="rounded-2xl"
                    />
                    <div
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="100"
                        class="flex flex-col justify-center"
                    >
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
            <LandingSection id="advantages">
                <div
                    class="grid grid-cols-1 gap-8 mx-auto lg:gap-14 xl:grid-cols-2 max-w-7xl"
                >
                    <div
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="200"
                        class="flex flex-col justify-center"
                    >
                        <h1 class="mb-4 text-2xl font-bold sm:text-3xl">
                            Kenapa memilih Kami?
                        </h1>
                        <p class="text-gray-700">
                            Kami memiliki keunggulan antara lain:
                        </p>
                    </div>
                    <div
                        data-aos="fade-up"
                        data-aos-duration="600"
                        data-aos-delay="300"
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-9"
                    >
                        <AdvantageCard
                            v-for="(advantage, index) in props.store
                                ?.advantages || []"
                            :key="advantage.id"
                            :title="advantage.name"
                            :description="advantage.description"
                        />
                    </div>
                </div>
            </LandingSection>

            <!-- Certificates -->
            <LandingSection id="certificates">
                <div
                    data-aos="fade-up"
                    data-aos-duration="600"
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
