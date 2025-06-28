interface CartItemModel {
    product_id: number;
    variant_id: number;
    quantity: number;
    variant: ProductVariantEntity | null;
    created_at: string;
    updated_at?: string | null;
}
