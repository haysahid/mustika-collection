interface CartItemModel {
    product_id: number;
    variant_id: number;
    quantity: number;
    image: string | null;
    variant: ProductVariantEntity | null;
    created_at: string;
    updated_at?: string | null;
    selected: boolean;

    // Additional properties for UI state management
    showDeleteConfirmation?: boolean | null;
}
