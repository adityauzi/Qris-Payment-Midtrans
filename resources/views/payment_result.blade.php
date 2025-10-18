<!DOCTYPE html>
<html>
<head>
    <title>Detail Pembayaran</title>
    <style>
        /* CSS Umum */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Ubah ke flex-start agar konten dimulai dari atas */
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        /* Header dan Status */
        h1 {
            color: #1a1a1a;
            font-size: 1.8em;
            margin-bottom: 5px;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        
        /* Status pending */
        .status-pending {
            background-color: #fff3cd; 
            color: #856404;
        }

        /* Kartu Informasi */
        .info-card {
            background-color: #f8f9fa; /* Latar belakang untuk blok info */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-card p {
            margin: 5px 0;
            font-size: 1em;
            color: #495057;
        }
        
        .info-card strong {
            color: #1a1a1a;
            font-size: 1.1em;
            word-break: break-all; /* Memastikan VA Number tidak melebihi batas */
        }
        
        /* Bagian Instruksi */
        h2 {
            color: #007bff; /* Warna primer */
            font-size: 1.4em;
            margin-top: 30px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            border: 1px dashed #cccccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .qr-section img {
            width: 200px;
            height: 200px;
            border: 5px solid #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* Tombol Simulator (Link) */
        .simulator-button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #17a2b8; /* Info Blue */
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-top: 15px;
        }

        .simulator-button:hover {
            background-color: #138496;
        }
        
        /* Link Kembali */
        .footer-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-link:hover {
            text-decoration: underline;
        }
        
        /* Ikon sederhana (menggunakan unicode/emoji sebagai pengganti Font Awesome/SVG) */
        .icon {
            margin-right: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Transaksi</h1>
        
        <span class="status-badge status-pending">
            {{ $charge->transaction_status }}
        </span>

        <div class="info-card">
            <p>Order ID: <strong>#{{ $order_id }}</strong></p>
            <p>Metode: <strong>{{ strtoupper(str_replace('_', ' ', $charge->payment_type)) }}</strong></p>
            <p>Total Bayar: <strong>Rp{{ number_format($charge->gross_amount) }}</strong></p>
        </div>

        @if ($charge->payment_type == 'bank_transfer')
            <h2><span class="icon">🏦</span> Instruksi Bank Transfer BSI VA</h2>
            
            <div class="info-card" style="background-color: #e6f7ff; border: 1px solid #91d5ff;">
                <p>Nomor Virtual Account:</p>
                @foreach($charge->va_numbers as $va)
                    @if($va->bank == 'bca')
                        <strong style="font-size: 1.5em; color: #0056b3;">{{ $va->va_number }}</strong>
                    @endif
                @endforeach
                
                <hr style="margin: 15px 0; border-top: 1px dashed #c4d7e8;">
                
                <p>Batas Waktu Pembayaran:</p>
                <strong style="color: #dc3545; font-size: 1.2em;">
                    <span class="icon">🕒</span> {{ \Carbon\Carbon::parse($charge->expiry_time)->format('d M Y H:i:s') }}
                </strong>
            </div>

            <p style="margin-top: 20px; font-style: italic; color: #6c757d;">
                Mohon segera lakukan pembayaran sebelum batas waktu untuk menghindari pembatalan.
            </p>
            
            @if(isset($charge->bca_va) && isset($charge->bca_va->bca_code)) 
            @endif

        @elseif ($charge->payment_type == 'gopay')
            <h2><span class="icon">📱</span> Pembayaran GoPay / QRIS</h2>
            
            <p style="color: #495057;">Scan QR Code di bawah menggunakan aplikasi Gojek atau E-Wallet lain yang mendukung QRIS:</p>
            
            <div class="qr-section">
                @if(isset($charge->actions))
                    @foreach($charge->actions as $action)
                        @if($action->name == 'generate-qr-code')
                            <img src="{{ $action->url }}" alt="QR Code">
                        @endif
                    @endforeach
                @endif
                
                <p style="font-size: 0.9em; color: #6c757d;">
                    QR Code ini berlaku hingga {{ \Carbon\Carbon::parse($charge->expiry_time)->format('H:i:s') }}.
                </p>
            </div>
            
            @if(isset($charge->actions))
                @foreach($charge->actions as $action)
                    @if($action->name == 'deeplink-redirect')
                        <a href="{{ $action->url }}" target="_blank" class="simulator-button">
                            Bayar dengan GoPay di Simulator
                        </a>
                        <p style="text-align: center; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                            *(Anda akan diarahkan ke laman simulasi pembayaran Midtrans)*
                        </p>
                    @endif
                @endforeach
            @endif
        @endif
        
        <hr style="margin-top: 30px; border: 0; border-top: 1px solid #eeeeee;">
        <a href="/checkout" class="footer-link">← Pilih Pembayaran Lain</a>
    </div>
</body>
</html>