<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Invoice {{ $order['order_code'] ?? '' }}</title>
  <style>
    :root { --text:#1b1b18; --muted:#6b7280; --border:#e5e7eb; --bg:#ffffff; }
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;color:var(--text);background:var(--bg)}
    .container{width:794px;margin:0 auto;padding:40px}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
    .logo{display:flex;align-items:center;gap:12px}
    .logo img{height:44px;width:auto}
    .doc-no{font-size:14px;color:var(--muted)}
    .doc-title{font-size:48px;font-weight:800;letter-spacing:1px;margin:24px 0}
    .meta{display:flex;justify-content:space-between;margin-bottom:24px;gap:24px}
    .block{width:50%}
    .label{font-weight:600;margin-bottom:6px}
    .table{width:100%;border-collapse:collapse;margin-top:12px}
    .table th,.table td{padding:10px 12px;border-bottom:1px solid var(--border);text-align:left}
    .table th{background:#f3f4f6;font-weight:600}
    .qty,.price,.amount{text-align:right;white-space:nowrap}
    .total-row{font-weight:700}
    .foot{margin-top:24px}
    .badge{display:inline-block;font-size:12px;padding:6px 10px;background:#fde68a;color:#92400e;border-radius:999px}
    .note{font-size:13px;color:var(--muted)}
    @page{size:A4;margin:20mm}
    @media print{
      .container{width:auto;margin:0;padding:0}
      .btn-print{display:none}
    }
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

    <div class="doc-title">INVOICE</div>

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
        <div class="label">From:</div>
        <div>UPBS BRMP Biogen</div>
        <div>biogen.go.id</div>
        <div style="margin-top:16px" class="badge">Belum Dibayar</div>
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
            <td>
              {{ $it['resolved_variety_name'] ?? 'Varietas Tidak Diketahui' }}
            </td>
            <td class="qty">
              {{ (int)($it['quantity'] ?? 0) }}
            </td>
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
      <div class="note">Payment method: -</div>
      <div class="note" style="margin-top:6px">
        Note: Silakan selesaikan pembayaran sesuai instruksi yang berlaku.
      </div>
      <button class="btn-print" onclick="window.print()">Cetak</button>
    </div>
  </div>
</body>
</html>
