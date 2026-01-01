<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>
<body style="font-family: Arial, sans-serif">

    <h2>Invoice Pesanan</h2>

    <p><strong>Kode Pesanan:</strong> {{ $order['order_code'] }}</p>
    <p><strong>Status:</strong> {{ $order['status'] }}</p>
    <p><strong>Total:</strong>
        Rp {{ number_format($order['total_amount'], 0, ',', '.') }}
    </p>

    <hr>

    <h4>Detail Pembelian</h4>
    <ul>
        @foreach ($order['items'] as $item)
            <li>
                {{ $item['name'] }}
                ({{ $item['quantity'] }} x
                Rp {{ number_format($item['unit_price'], 0, ',', '.') }})
            </li>
        @endforeach
    </ul>

    <p style="margin-top:20px">
        Terima kasih telah berbelanja 🙏
    </p>

</body>
</html>
