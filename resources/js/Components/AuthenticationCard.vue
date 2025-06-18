<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center py-6 px-6 sm:py-0 sm:px-0 bg-gradient-to-t from-[#E0BEFF80] from-80% to-white relative"
    >
        <img
            src="/storage/couple.png"
            alt="Couple"
            class="absolute bottom-0 left-0 hidden object-cover h-auto md:block md:w-1/3"
        />
        <img
            src="/storage/clothes_rack.png"
            alt=""
            class="absolute bottom-0 right-0 hidden object-cover h-auto md:block md:w-1/3"
        />
        <div
            class="z-10 w-full px-8 py-8 overflow-hidden bg-white shadow-md sm:max-w-sm rounded-2xl"
        >
            <div class="w-full mx-auto">
                <slot name="logo" />
            </div>
            <slot />
        </div>
    </div>
</template>
