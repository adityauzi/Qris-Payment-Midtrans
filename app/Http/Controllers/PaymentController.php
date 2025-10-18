<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config; // 👈 Pastikan ini ada
use Midtrans\CoreApi; // Jika Anda menggunakannya

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans menggunakan file config yang dibuat
       Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function showPaymentForm()
    {
        return view('payment_form'); // View untuk memilih metode pembayaran
    }

    public function processPayment(Request $request)
    {
        $payment_method = $request->input('payment_method');
        $order_id = time() . '-' . uniqid(); // Order ID unik

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 15000, // Contoh: Rp15.000
            ],
            'customer_details' => [
                'first_name' => 'Demo',
                'last_name' => 'User',
                'email' => 'demo@example.com',
                'phone' => '081234567890',
            ],
        ];

        try {
            if ($payment_method == 'bca_va') {
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => 'bca'];
            } elseif ($payment_method == 'gopay') {
                $params['payment_type'] = 'gopay';
            } else {
                return redirect()->back()->withErrors('Metode pembayaran tidak valid.');
            }

            // Panggil CoreApi::charge untuk Midtrans Core API
            $charge = CoreApi::charge($params);

            // Jika berhasil, kirim data charge ke view
            return view('payment_result', compact('charge', 'order_id'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
}