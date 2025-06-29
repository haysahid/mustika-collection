interface TransactionTypeEntity {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    effect_on_stock: string;
    created_at: string | null;
    updated_at: string | null;

    // Relationships
    transactions?: TransactionEntity[] | null;
}
