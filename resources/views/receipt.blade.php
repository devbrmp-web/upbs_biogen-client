<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kwitansi {{ $order['order_code'] ?? '' }}</title>
  <style>
    :root { --text:#1b1b18; --muted:#6b7280; --border:#e5e7eb; --bg:#ffffff; }
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;color:var(--text);background:var(--bg)}
    .container{width:794px;margin:0 auto;padding:40px}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
    .logo{display:flex;align-items:center;gap:12px}
    .logo img{height:44px;width:auto}
    .doc-no{font-size:14px;color:var(--muted)}
    .doc-title{font-size:32px;font-weight:800;letter-spacing:1px;margin:24px 0}
    .meta{display:flex;justify-content:space-between;margin-bottom:24px;gap:24px}
    .block{width:50%}
    .label{font-weight:600;margin-bottom:6px}
    .table{width:100%;border-collapse:collapse;margin-top:12px}
    .table th,.table td{padding:10px 12px;border-bottom:1px solid var(--border);text-align:left}
    .table th{background:#f3f4f6;font-weight:600}
    .qty,.price,.amount{text-align:right;white-space:nowrap}
    .total-row{font-weight:700}
    .foot{margin-top:24px}
    .badge{display:inline-block;font-size:12px;padding:6px 10px;background:#d1fae5;color:#065f46;border-radius:999px}
    .note{font-size:13px;color:var(--muted)}
    @page{size:A4;margin:20mm}
    @media print{.container{width:auto;margin:0;padding:0}.btn-print{display:none}}
    .btn-print{margin-top:12px;padding:10px 14px;background:#111827;color:#fff;border-radius:8px;border:0}
  </style>
  </head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">
        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="UPBS BRMP Biogen" />
        <div style="font-weight:700">UPBS BRMP Biogen</div>
      </div>
      <div class="doc-no">NO. {{ $order['order_code'] ?? '-' }}</div>
    </div>

    <div class="doc-title">KWITANSI PEMBAYARAN</div>

    <div class="meta">
      <div class="block">
        <div class="label">Date:</div>
        <div>{{ now('Asia/Jakarta')->format('d F Y') }}</div>
        <div class="label" style="margin-top:16px">Billed to:</div>
        <div>{{ $order['customer_name'] ?? '-' }}</div>
        <div>{{ $order['customer_address'] ?? '-' }}</div>
        <div>{{ $order['customer_phone'] ?? '-' }}</div>
      </div>
      <div class="block">
        @php
            // ============================================
            // STATUS LABEL LOGIC (3 states)
            // ============================================
            // 1. Lunas: paid, processing, pickup_ready, completed, shipped
            // 2. Menunggu Verifikasi: pending_verification
            // 3. Belum Dibayar: awaiting_payment, or other
            
            $paidStatuses = ['paid', 'processing', 'pickup_ready', 'completed', 'shipped'];
            $currentStatus = $order['status'] ?? '';
            
            if (in_array($currentStatus, $paidStatuses)) {
                $statusLabel = 'Telah Dibayar';
                $statusBgColor = '#d1fae5';  // green-100
                $statusTextColor = '#065f46'; // green-800
            } elseif ($currentStatus === 'pending_verification') {
                $statusLabel = 'Menunggu Verifikasi';
                $statusBgColor = '#ffedd5';  // orange-100
                $statusTextColor = '#c2410c'; // orange-700
            } else {
                $statusLabel = 'Belum Dibayar';
                $statusBgColor = '#fef3c7';  // amber-100
                $statusTextColor = '#92400e'; // amber-700
            }
            
            // Get payment method - prioritize payment record, fallback to order
            $paymentMethod = $payment['payment_method'] ?? $order['payment_type'] ?? 'Bank Transfer';
            if (empty($paymentMethod) || $paymentMethod === '-') {
                $paymentMethod = 'Bank Transfer';
            }
            
            // Get transaction ID - use order_code as fallback for manual payments
            $transactionId = $payment['transaction_id'] ?? $order['transaction_id'] ?? $order['order_code'] ?? '-';
            
            // Get paid_at date
            $paidAt = $payment['paid_at'] ?? $order['paid_at'] ?? $order['settlement_time'] ?? null;
            if ($paidAt && is_string($paidAt)) {
                try {
                    $paidAt = \Carbon\Carbon::parse($paidAt)->format('d F Y, H:i');
                } catch (\Exception $e) {
                    // Keep as string if parsing fails
                }
            }
        @endphp
        <div class="label">Payment</div>
        <div>Metode: {{ ucwords(str_replace('_', ' ', $paymentMethod)) }}</div>
        <div>ID Transaksi: {{ $transactionId }}</div>
        <div>Tanggal Bayar: {{ $paidAt ?: '-' }}</div>
        <div>PNBP: {{ $payment['pnbp_receipt_no'] ?? $order['pnbp_receipt_no'] ?? '-' }}</div>
        <div style="margin-top:16px;background:{{ $statusBgColor }};color:{{ $statusTextColor }}" class="badge">
            {{ $statusLabel }}
        </div>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Item</th>
          <th class="qty">Quantity</th>
          <th class="price">Price</th>
          <th class="amount">Amount</th>
        </tr>
      </thead>
      <tbody>
@foreach(($order['items'] ?? []) as $it)
<tr>
    <td>{{ $it['resolved_variety_name'] ?? 'Item' }}</td>
    <td class="qty">{{ (int)($it['quantity'] ?? 0) }}</td>
    <td class="price">
        Rp {{ number_format((int)($it['unit_price'] ?? 0), 0, ',', '.') }}
    </td>
    <td class="amount">
        Rp {{ number_format(
            ((int)($it['unit_price'] ?? 0)) * ((int)($it['quantity'] ?? 0)),
            0, ',', '.'
        ) }}
    </td>
</tr>
@endforeach

<tr class="total-row">
    <td colspan="3" style="text-align:right">Total</td>
    <td class="amount">
        Rp {{ number_format((int)($order['total_amount'] ?? 0), 0, ',', '.') }}
    </td>
</tr>
</tbody>

    </table>

    <div class="foot">
      <div class="note">Terima kasih telah melakukan pembayaran.</div>
      <button class="btn-print" onclick="window.print()">Cetak</button>
    </div>
  </div>
</body>
</html>
