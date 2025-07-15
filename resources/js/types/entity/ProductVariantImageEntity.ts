interface ProductVariantImageEntity {
    id: number | string | null;
    product_variant_id: number | null;
    product_id: number;
    image: string | File | null;
    order: number;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;
}
