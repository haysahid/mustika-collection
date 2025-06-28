import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useCartStore = defineStore("cart_items", () => {
    const items = ref(
        localStorage.getItem("cart_items")
            ? (JSON.parse(
                  localStorage.getItem("cart_items")
              ) as CartItemModel[])
            : []
    );

    const selectedItems = computed(() => {
        return items.value.filter((item) => item.selected);
    });

    const subTotal = computed(() => {
        return selectedItems.value.reduce((total, item) => {
            const price = item.variant?.final_selling_price || 0;
            return total + price * item.quantity;
        }, 0);
    });

    const toggleItem = (item: CartItemModel) => {
        const index = items.value.findIndex(
            (i) => i.variant_id === item.variant_id
        );
        if (index !== -1) {
            items.value[index].selected = !items.value[index].selected;
        }
        localStorage.setItem("cart_items", JSON.stringify(items.value));
    };

    const addItem = (item: CartItemModel) => {
        items.value.unshift(item);
        localStorage.setItem("cart_items", JSON.stringify(items.value));
    };

    const updateItem = (item: CartItemModel) => {
        const index = items.value.findIndex(
            (i) => i.variant_id === item.variant_id
        );
        if (index !== -1) {
            items.value[index] = item;
            localStorage.setItem("cart_items", JSON.stringify(items.value));
        }
    };

    const updateItemByCreatedAt = (createdAt: string, item: CartItemModel) => {
        const index = items.value.findIndex((i) => i.created_at === createdAt);
        if (index !== -1) {
            items.value[index] = item;
            localStorage.setItem("cart_items", JSON.stringify(items.value));
        }
    };

    const removeItem = (item: CartItemModel) => {
        items.value = items.value.filter(
            (i) => i.variant_id !== item.variant_id
        );
        localStorage.setItem("cart_items", JSON.stringify(items.value));
    };

    const clearCart = () => {
        items.value = [];
        localStorage.setItem("cart_items", JSON.stringify(items.value));
    };

    const getItemByVariant = (item: ProductVariantEntity): CartItemModel => {
        const foundItem = items.value.find((i) => i.variant_id === item?.id);
        if (foundItem) return foundItem;
        return null;
    };

    const updateAllItems = (newItems: CartItemModel[]) => {
        items.value = newItems;
        localStorage.setItem("cart_items", JSON.stringify(items.value));
    };

    return {
        items,
        selectedItems,
        subTotal,
        toggleItem,
        addItem,
        updateItem,
        updateItemByCreatedAt,
        removeItem,
        clearCart,
        getItemByVariant,
        updateAllItems,
    };
});
