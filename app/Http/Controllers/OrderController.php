<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function orderSuccess($invoice_code)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $invoice = Invoice::where('code', $invoice_code)->firstOrFail();
        $transaction = Transaction::with(['payment_method', 'shipping_method'])
            ->find($invoice->transaction_id);
        $items = TransactionItem::where('transaction_id', $transaction->id)->get();

        return Inertia::render('OrderSuccess', [
            'invoice' => $invoice,
            'transaction' => $transaction,
            'items' => $items,
            'store' => $store,
        ]);
    }
}
