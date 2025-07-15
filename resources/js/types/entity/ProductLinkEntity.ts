interface ProductLinkEntity {
    id: number;
    product_id: number;
    platform_id: number;
    url: string;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;

    // Relationships
    platform: PlatformEntity | null;
}
