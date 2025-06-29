interface TransactionEntity {
    id: number;
    type_id: number;
    code: string;
    note: string | null;
    payment_method_id: number;
    shipping_method_id: number;
    province_id: number | null;
    province_name: string | null;
    city_id: number | null;
    city_name: string | null;
    address: string | null;
    shipping_cost: number;
    shipping_estimate: string | null;
    status: string;
    created_at: string | null;
    updated_at: string | null;

    // Relationships
    user: UserEntity | null;
    items: TransactionItemEntity[];
    type: TransactionTypeEntity | null;
    payment_method: PaymentMethodEntity | null;
    shipping_method: ShippingMethodEntity | null;
    invoices: InvoiceEntity[];
    payments: PaymentEntity[];
}
