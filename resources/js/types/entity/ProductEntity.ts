interface ProductEntity {
    id: number;
    name: string;
    brand_id: number | null;
    slug: string;
    sku_prefix: string;
    description: string;
    discount_type: string;
    discount: number;

    // Additional attributes
    lowest_base_selling_price: number;
    lowest_final_selling_price: number;
    highest_base_selling_price: number;
    highest_final_selling_price: number;

    // Relationships
    brand: BrandEntity | null;
    categories: CategoryEntity[];
    images: ProductVariantImageEntity[];
    links: ProductLinkEntity[];
    variants: ProductVariantEntity[];
}
