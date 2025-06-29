interface RoleEntity {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    created_at: string | null;
    updated_at: string | null;

    // Relationships
    users?: UserEntity[] | null;
}
