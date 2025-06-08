<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center py-6 px-6 sm:py-0 sm:px-0 bg-gradient-to-t from-[#E0BEFF80] from-80% to-white relative"
    >
        <img
            src="/storage/couple.png"
            alt="Couple"
            class="absolute bottom-0 left-0 hidden md:block md:w-1/3 h-auto object-cover"
        />
        <img
            src="/storage/clothes_rack.png"
            alt=""
            class="absolute bottom-0 right-0 hidden md:block md:w-1/3 h-auto object-cover"
        />
        <div
            class="w-full sm:max-w-sm px-8 py-8 bg-white shadow-md overflow-hidden rounded-2xl z-10"
        >
            <div class="w-full mx-auto">
                <slot name="logo" />
            </div>
            <slot />
        </div>
    </div>
</template>
