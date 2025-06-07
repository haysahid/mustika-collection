<script setup>
import LandingLayout from "@/Layouts/LandingLayout.vue";
import AdvantageCard from "@/Components/AdvantageCard.vue";
import ProductCard from "@/Components/ProductCard.vue";
import LandingSection from "@/Components/LandingSection.vue";
</script>

<template>
    <LandingLayout title="Home">
        <!-- Banner -->
        <img
            src="/storage/promotion_banner.png"
            alt="Banner"
            class="w-full object-cover"
        />

        <!-- Brands -->
        <div
            class="bg-[#E0BEFF80] flex max-sm:flex-wrap flex-row items-center justify-center gap-4 sm:gap-8 md:gap-24 py-4 sm:py-6 px-6 lg:px-40 [&>img]:max-w-16 [&>img]:sm:max-w-20 [&>img]:lg:max-w-32 [&>img]:h-8 [&>img]:sm:h-16 [&>img]:lg:h-20 [&>img]:object-contain"
        >
            <img src="/storage/brand/brand_1.png" alt="Logo brand 1" />
            <img src="/storage/brand/brand_2.png" alt="Logo brand 2" />
            <img src="/storage/brand/brand_3.png" alt="Logo brand 3" />
            <img src="/storage/brand/brand_4.png" alt="Logo brand 4" />
            <img src="/storage/brand/brand_5.png" alt="Logo brand 5" />
        </div>

        <div class="p-6 sm:px-12 md:p-[100px] flex flex-col gap-12 lg:gap-20">
            <!-- About Us -->
            <LandingSection>
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 max-w-7xl mx-auto"
                >
                    <img src="/storage/store.png" alt="" class="rounded-2xl" />
                    <div class="flex flex-col justify-center">
                        <h1 class="text-2xl sm:text-3xl font-bold mb-4">
                            Tentang Kami
                        </h1>
                        <p class="text-gray-700">
                            Kami adalah platform yang menghubungkan para
                            pengrajin lokal dengan pelanggan yang mencari produk
                            unik dan berkualitas. Dengan misi untuk
                            memberdayakan komunitas lokal, kami menyediakan
                            ruang bagi para pengrajin untuk menampilkan karya
                            mereka dan menjangkau pasar yang lebih luas.
                        </p>
                    </div>
                </div>
            </LandingSection>

            <!-- Store Advantages -->
            <LandingSection>
                <div
                    class="gap-8 lg:gap-14 grid grid-cols-1 xl:grid-cols-2 max-w-7xl mx-auto"
                >
                    <div class="flex flex-col justify-center">
                        <h1 class="text-2xl sm:text-3xl font-bold mb-4">
                            Kenapa memilih Kami?
                        </h1>
                        <p class="text-gray-700">
                            Kami memiliki keunggulan antara lain:
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-9">
                        <AdvantageCard
                            title="Produk Berkualitas"
                            description="Kami hanya bekerja sama dengan pengrajin yang memiliki standar kualitas tinggi, memastikan setiap produk yang Anda beli adalah karya terbaik."
                        />
                        <AdvantageCard
                            title="Produk Berkualitas"
                            description="Kami hanya bekerja sama dengan pengrajin yang memiliki standar kualitas tinggi, memastikan setiap produk yang Anda beli adalah karya terbaik."
                        />
                        <AdvantageCard
                            title="Produk Berkualitas"
                            description="Kami hanya bekerja sama dengan pengrajin yang memiliki standar kualitas tinggi, memastikan setiap produk yang Anda beli adalah karya terbaik."
                        />
                        <AdvantageCard
                            title="Produk Berkualitas"
                            description="Kami hanya bekerja sama dengan pengrajin yang memiliki standar kualitas tinggi, memastikan setiap produk yang Anda beli adalah karya terbaik."
                        />
                    </div>
                </div>
            </LandingSection>

            <!-- Certificates -->
            <LandingSection>
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 max-w-7xl mx-auto"
                >
                    <div class="flex flex-col items-start justify-center gap-4">
                        <img
                            src="/storage/certificate.png"
                            alt=""
                            class="rounded-2xl"
                        />
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-4 bg-primary rounded-full"></div>
                            <div
                                class="w-4 h-4 bg-[#F8E4F3] rounded-full"
                            ></div>
                            <div
                                class="w-4 h-4 bg-[#F8E4F3] rounded-full"
                            ></div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class="text-2xl sm:text-3xl font-bold mb-4">
                            Terbukti Produk Original
                        </h1>
                        <p class="text-gray-700">
                            Kami telah mendapatkan sertifikasi dari berbagai
                            lembaga yang mengakui keaslian dan kualitas produk
                            kami. Setiap produk yang kami tawarkan telah melalui
                            proses verifikasi yang ketat untuk memastikan bahwa
                            Anda mendapatkan produk yang benar-benar original
                            dan berkualitas tinggi.
                        </p>
                    </div>
                </div>
            </LandingSection>

            <!-- Best Seller Products -->
            <LandingSection>
                <div
                    class="flex flex-col items-start justify-center gap-4 max-w-7xl mx-auto"
                >
                    <div class="flex items-center justify-between gap-4 w-full">
                        <h1 class="text-2xl sm:text-3xl font-bold mb-4">
                            Produk Terlaris
                        </h1>
                        <a
                            href="#"
                            class="text-primary hover:underline text-sm flex items-center justify-center gap-1"
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
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-9 w-full"
                    >
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                        <ProductCard
                            name="Produk 1"
                            :price="100000"
                            image="/storage/product/product_1.png"
                            description="Deskripsi produk 1"
                            :discount="20"
                        />
                    </div>
                </div>
            </LandingSection>

            <!-- Join Us -->
            <LandingSection>
                <div
                    id="join"
                    class="w-full max-w-7xl mx-auto rounded-xl bg-[url(/storage/join_banner.png)] bg-cover bg-center bg-no-repeat h-[612px] flex items-center justify-center"
                >
                    <div
                        class="text-center max-w-[400px] flex flex-col items-center gap-6 px-4"
                    >
                        <h1 class="text-4xl sm:text-5xl font-bold">
                            Dapatkan Banyak Diskon!
                        </h1>
                        <p class="text-gray-700">
                            Bergabunglah dengan komunitas kami dan nikmati
                            berbagai keuntungan sebagai anggota. Dapatkan akses
                            eksklusif ke produk terbaru, diskon khusus, dan
                            banyak lagi!
                        </p>
                        <div
                            class="flex flex-col items-center justify-center gap-4"
                        >
                            <a
                                href="#"
                                class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary/90 transition duration-300 font-semibold"
                            >
                                Gabung Agen
                            </a>
                            <a
                                href="#"
                                class="bg-[#E4CFF6] text-primary px-6 py-3 rounded-md hover:bg-[#E4CFF6]/80 transition duration-300 font-semibold"
                            >
                                Gabung Reseller
                            </a>
                        </div>
                    </div>
                </div>
            </LandingSection>
        </div>
    </LandingLayout>
</template>
