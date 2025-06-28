interface ProductImageEntity {
    id: number;
    product_id: number;
    image: string;
    order: number;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;
}