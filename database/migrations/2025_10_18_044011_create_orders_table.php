<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Kolom yang ditambahkan/dimodifikasi
            $table->string('order_code')->unique(); // Kode unik transaksi untuk Midtrans
            $table->integer('amount');              // Jumlah pembayaran (gross_amount)
            $table->string('status')->default('pending'); // Status transaksi: pending, settlement, expire, cancel
            $table->string('midtrans_transaction_id')->nullable(); // ID transaksi dari Midtrans (opsional)
            $table->string('payment_type')->nullable(); // Jenis pembayaran (misal: qris, gopay)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};