<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function orderSuccess(Request $request)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $orderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');

        if ($transactionStatus === 'settlement') {
            $transactionId = $orderId;

            $transaction = Transaction::with(['payment_method', 'shipping_method'])->findOrFail($transactionId);
            $invoice = Invoice::where('transaction_id', $transaction->id)->latest()->firstOrFail();

            // Check midtrans payment status
            if ($transaction->payment_method->slug === 'transfer') {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                \Midtrans\Config::$is3ds = true;

                $response = (object) \Midtrans\Transaction::status($transaction->code);

                if ($response->transaction_status !== 'settlement') {
                    throw new Exception('Pembayaran belum terkonfirmasi');
                }

                // Create payment record
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $transaction->payment_method_id,
                    'amount' => $invoice->amount,
                    'midtrans_response' => json_encode($response),
                    'status' => 'completed',
                ]);

                // Update invoice paid_at
                $invoice->paid_at = now();
                $invoice->save();

                // Update transaction status
                $transaction->paid_at = now();
                $transaction->status = 'paid';
                $transaction->save();

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                return Inertia::render('OrderSuccess', [
                    'invoice' => $invoice,
                    'transaction' => $transaction,
                    'items' => $items,
                    'store' => $store,
                ]);
            }
        }

        $invoice_code = $request->query('invoice_code');
        $transaction_code = $request->query('transaction_code') ?? $orderId;


        if ($invoice_code) {
            $invoice = Invoice::where('code', $invoice_code)->firstOrFail();
            $transaction = Transaction::with(['payment_method', 'shipping_method'])
                ->find($invoice->transaction_id);
            $items = TransactionItem::where('transaction_id', $transaction->id)->get();
        } else if ($transaction_code) {
            $transaction = Transaction::with(['payment_method', 'shipping_method'])
                ->where('code', $transaction_code)
                ->firstOrFail();
            $invoice = Invoice::where('transaction_id', $transaction->id)->first();
            $items = TransactionItem::where('transaction_id', $transaction->id)->get();
        } else {
            Log::error('No invoice or transaction code provided for order success.');
            return redirect()->route('my-order')->with('error', 'Invalid order details.');
        }

        return Inertia::render('OrderSuccess', [
            'invoice' => $invoice,
            'transaction' => $transaction,
            'items' => $items,
            'store' => $store,
        ]);
    }

    public function myOrder(Request $request)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $transactions = Transaction::with(['payment_method', 'shipping_method', 'items'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('MyOrder', [
            'transactions' => $transactions,
            'store' => $store,
        ]);
    }

    public function myOrderDetail($transaction_code)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        if ($transaction_code) {
            $transaction = Transaction::with(['payment_method', 'shipping_method'])
                ->where('code', $transaction_code)
                ->firstOrFail();
            $invoice = Invoice::where('transaction_id', $transaction->id)->first();
            $items = TransactionItem::where('transaction_id', $transaction->id)->get();
        } else {
            Log::error('No invoice or transaction code provided for order success.');
            return redirect()->route('my-order')->with('error', 'Invalid order details.');
        }

        return Inertia::render('MyOrderDetail', [
            'invoice' => $invoice,
            'transaction' => $transaction,
            'items' => $items,
            'store' => $store,
        ]);
    }
}
