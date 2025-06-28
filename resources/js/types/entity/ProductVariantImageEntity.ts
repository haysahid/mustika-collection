interface ProductVariantImageEntity {
    id: number;
    product_variant_id: number | null;
    product_id: number;
    image: string;
    order: number;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;
}
