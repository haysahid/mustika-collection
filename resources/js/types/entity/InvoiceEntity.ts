interface InvoiceEntity {
    id: number;
    transaction_id: number;
    code: string;
    amount: number;
    tax: number;
    due_date: string;
    snap_token?: string;
    updated_at: string;
    created_at: string;

    // Relationships
    transaction: TransactionEntity | null;
}
