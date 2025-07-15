interface PaymentEntity {
    id: number;
    invoice_id: number;
    transaction_id: number;
    payment_method_id: number;
    amount: number;
    note: string | null;
    reason: string | null;
    image: string | null;
    midtrans_response: string | null;
    status: string;
    created_at: string | null;
    updated_at: string | null;

    // Relationships
    invoice?: InvoiceEntity | null;
    transaction?: TransactionEntity | null;
    payment_method?: PaymentMethodEntity | null;
}
