import { watch, computed } from "vue";
import { defineStore } from "pinia";
import { useForm } from "@inertiajs/vue3";

export const useProductFormStore = defineStore("product_form", () => {
    const form = useForm(
        localStorage.getItem("product_form")
            ? JSON.parse(localStorage.getItem("product_form"))
            : {
                  name: null,
                  brand_id: null,
                  brand: null,
                  discount: 0,
                  description: null,
                  categories: [],
                  images: [{ id: "new-1", image: null }],
                  links: [],
                  variants: [],
              }
    );

    const initForm = (product: ProductEntity) => {
        form.reset();
        form.name = product.name;
        form.brand_id = product.brand_id;
        form.brand = product.brand;
        form.discount = product.discount || 0;
        form.description = product.description;
        form.categories = product.categories || [];
        form.images = [
            ...(product?.images?.map((image) => ({
                ...image,
                image: "/storage/" + image.image,
            })) || []),
            { id: "new-1", image: null },
        ];
        form.links = [
            ...(product?.links?.map(function (link) {
                if (!link.platform) return link;
                return {
                    ...link,
                    platform: {
                        ...link.platform,
                        icon: "/storage/" + link.platform.icon,
                    },
                };
            }) || []),
        ];
        form.variants = [
            ...(product?.variants?.map((variant) => ({
                ...variant,
                images:
                    variant.images?.map((image) => ({
                        ...image,
                        image: "/storage/" + image.image,
                    })) || [],
            })) || []),
        ];
        localStorage.setItem("product_form", JSON.stringify(form));
    };

    watch(
        form,
        (newForm) => {
            localStorage.setItem("product_form", JSON.stringify(newForm));
        },
        { deep: true }
    );

    const isNewImage = (image) => {
        return typeof image.id == "string" && image.id.startsWith("new-");
    };

    const isExistingImage = (image) => {
        return typeof image.id == "number";
    };

    const countNewImages = computed(() => {
        return form.images.filter((image) => isNewImage(image)).length;
    });

    const resetForm = () => {
        form.reset();
        localStorage.removeItem("product_form");
    };

    return {
        form,
        initForm,
        isNewImage,
        isExistingImage,
        countNewImages,
        resetForm,
    };
});
