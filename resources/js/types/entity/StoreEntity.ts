interface StoreEntity {
    id: number;
    name: string;
    description: string;
    address: string;
    phone: string;
    logo: string;
    banner: string;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;

    // Relationships
    advantages: StoreAdvantageEntity[];
    certificates: string[];
    social_links: StoreSocialLinkEntity[];
}
