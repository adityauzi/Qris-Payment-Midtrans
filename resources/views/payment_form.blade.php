<!DOCTYPE html>
<html>
<head>
    <title>Demo Pembayaran Midtrans</title>
    <style>
        /* CSS untuk tampilan modern */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6; /* Warna latar belakang lembut */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #1a1a1a;
            font-size: 1.8em;
            margin-bottom: 25px;
            text-align: center;
        }

        label {
            display: block;
            font-size: 1em;
            color: #555555;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .payment-option {
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        .payment-option:hover {
            border-color: #007bff; /* Highlight saat hover */
            background-color: #f8faff;
        }

        .payment-option input[type="radio"] {
            margin-right: 15px;
            /* Menyembunyikan radio button bawaan dan menggunakan styling kustom jika perlu */
            /* Untuk tampilan modern, seringkali menggunakan CSS untuk styling kustom */
            appearance: none; /* Menyembunyikan radio button asli */
            width: 18px;
            height: 18px;
            border: 2px solid #999;
            border-radius: 50%;
            outline: none;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
            flex-shrink: 0; /* Mencegah mengecil */
        }

        .payment-option input[type="radio"]:checked {
            border-color: #007bff;
            background-color: #007bff;
        }
        
        /* Titik putih di tengah radio saat checked */
        .payment-option input[type="radio"]:checked::after {
            content: '';
            width: 6px;
            height: 6px;
            background-color: #fff;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .payment-label {
            color: #333;
            font-weight: 400;
            font-size: 0.95em;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #138496; 
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #138496;
        }

        .error-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background-color: #ffe0e6; /* Latar belakang merah muda lembut */
            color: #d9304e; /* Warna teks merah yang jelas */
            border: 1px solid #f8c9d0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout Demo - Rp15.000 💳</h1>
        <form method="POST" action="/payment/process">
            @csrf
            <label>Pilih Metode Pembayaran:</label>
            
            <div class="payment-option">
                <input type="radio" name="payment_method" id="bca_va" value="bca_va" required>
                <label for="bca_va" class="payment-label">🏦 Bank Transfer BSI Virtual Account</label>
            </div>
            
            <div class="payment-option">
                <input type="radio" name="payment_method" id="gopay" value="gopay" required>
                <label for="gopay" class="payment-label">📱 GoPay / QRIS</label>
            </div>

            <button type="submit">Bayar Sekarang</button>
        </form>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>