interface TransactionItemEntity {
    id: number;
    transaction_id: number;
    product_id: number;
    product_variant_id: number | null;
    quantity: number;
    base_selling_price: number;
    final_selling_price: number;
    discount_type: string | null;
    discount: number | null;
    total_amount: number;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;

    // Relationships
    product: ProductEntity | null;
    product_variant: ProductVariantEntity | null;
}
