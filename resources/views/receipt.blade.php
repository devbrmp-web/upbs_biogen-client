<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kuitansi Resmi {{ $order['order_code'] ?? '' }}</title>
  <style>
    /* A4 Paper Dimensions and Base Settings */
    @page {
        size: A4 portrait;
        margin: 15mm 15mm 20mm 15mm;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9.5pt;
        line-height: 1.4;
        color: #333;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: #f3f4f6;
    }

    * {
        box-sizing: border-box;
    }

    .container {
        width: 794px;
        margin: 30px auto;
        padding: 40px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-radius: 8px;
        position: relative;
    }

    .page-break-avoid {
        page-break-inside: avoid;
    }

    /* Watermark Background */
    .watermark-container {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        z-index: 1; 
        text-align: center;
        width: 100%;
        pointer-events: none;
    }

    .watermark-badge {
        font-weight: bold;
        font-size: 72pt;
        border: 8px solid;
        padding: 20px 40px;
        display: inline-block;
        text-align: center;
    }
    
    .watermark-badge.paid {
        color: rgba(46, 125, 50, 0.12); /* Forest Green opacity */
        border-color: rgba(46, 125, 50, 0.12);
    }

    .watermark-badge.unpaid {
        color: rgba(220, 53, 69, 0.12); /* Red opacity */
        border-color: rgba(220, 53, 69, 0.12);
    }

    /* Professional Header (Kop Surat) */
    .header-official {
        width: 100%;
        margin-bottom: 15px;
        display: table;
        page-break-after: avoid;
    }

    .header-logo {
        display: table-cell;
        vertical-align: middle;
        width: 90px;
    }

    .header-logo img {
        width: 80px;
        height: auto;
    }

    .header-text {
        display: table-cell;
        vertical-align: middle;
        text-align: left;
        padding-left: 15px;
    }

    .header-text h1 {
        font-size: 13pt;
        font-weight: bold;
        margin: 0 0 4px 0;
        color: #2E7D32; /* Forest Green Branding */
    }

    .header-text p.address {
        font-size: 8.5pt;
        margin: 0;
        color: #555;
        line-height: 1.3;
    }

    .header-line {
        border-top: 3px solid #2E7D32;
        border-bottom: 1px solid #2E7D32;
        height: 2px;
        margin-top: 10px;
        margin-bottom: 20px;
        padding: 0;
    }

    /* Document Title */
    .doc-title {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        margin-bottom: 20px;
        color: #2E7D32;
        letter-spacing: 2px;
    }

    /* 2-Column Info Table */
    .info-table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
    }

    .info-table td {
        vertical-align: top;
        padding: 4px 0;
        font-size: 9.5pt;
    }

    .info-table .col-left {
        width: 50%;
        padding-right: 15px;
    }

    .info-table .col-right {
        width: 50%;
        padding-left: 15px;
    }

    .info-table .label {
        font-weight: bold;
        color: #555;
        display: inline-block;
        width: 120px;
    }

    /* Items Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 9.5pt;
        position: relative;
        z-index: 2;
    }

    .items-table th {
        background-color: #2E7D32; /* Forest Green */
        color: #ffffff;
        font-weight: bold;
        text-align: center;
        padding: 10px 8px;
        border: 1px solid #1B5E20;
    }

    .items-table td {
        border-bottom: 1px solid #eee;
        border-left: 1px solid #eee;
        border-right: 1px solid #eee;
        padding: 10px 8px;
        vertical-align: middle;
    }

    /* Zebra striping specific to modern agriculture theme */
    .items-table tbody tr:nth-child(even) {
        background-color: #E8F5E9; /* Light green tint */
    }

    .items-table td.text-right { text-align: right; }
    .items-table td.text-center { text-align: center; }

    .items-table .product-name {
        font-weight: bold;
        color: #000;
        margin-bottom: 2px;
        display: block;
    }

    .items-table .product-meta {
        font-size: 8.5pt;
        color: #666;
    }

    /* Calculation Summary */
    .summary-container {
        width: 100%;
        margin-top: 15px;
        position: relative;
        z-index: 2;
    }

    .summary-table {
        width: 45%; 
        float: right;
        border-collapse: collapse;
        font-size: 9.5pt;
    }

    .summary-table td {
        padding: 6px 8px;
        border-bottom: 1px solid #eee;
    }

    .summary-table .label {
        font-weight: bold;
        text-align: right;
        color: #555;
    }

    .summary-table .amount {
        text-align: right;
        width: 120px;
        color: #000;
    }

    .summary-table .grand-total-row {
        background-color: #2E7D32;
    }

    .summary-table .grand-total-row td {
        color: #ffffff;
        font-size: 11pt;
        font-weight: bold;
        border-bottom: none;
        padding-top: 8px;
        padding-bottom: 8px;
    }

    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Decorative Bottom Line */
    .decorative-line {
        height: 4px;
        background-color: #2E7D32;
        width: 100%;
        margin-top: 30px;
    }

    /* Footer Validation */
    .footer-validation {
        text-align: center;
        font-size: 8.5pt;
        color: #666;
        padding-top: 15px;
    }
    
    .qr-image {
        width: 75px;
        height: 75px;
        margin: 8px auto;
        display: block;
        border: 1px solid #eee;
        padding: 3px;
        background: #fff;
    }
    
    .qr-text {
        font-size: 7.5pt;
        color: #777;
        margin-top: 3px;
    }

    .slogan {
        font-weight: bold;
        color: #2E7D32;
        font-size: 9.5pt;
        margin-top: 10px;
        font-style: italic;
    }

    .badge {
        display: inline-block;
        font-size: 8.5pt;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .btn-print {
        margin-top: 20px;
        padding: 10px 18px;
        background: #2E7D32;
        color: #fff;
        font-weight: bold;
        border-radius: 6px;
        border: 0;
        cursor: pointer;
        font-size: 10pt;
        transition: background 0.2s;
        display: inline-block;
    }
    
    .btn-print:hover {
        background: #1B5E20;
    }

    .foot {
        margin-top: 25px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    /* Print Overrides */
    @media print {
        body {
            background: #ffffff;
            font-size: 9.5pt;
        }
        .container {
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border-radius: 0;
        }
        .btn-print {
            display: none;
        }
    }
  </style>
</head>
<body>
  @php
      // ============================================
      // 🧠 MASTER DATA COMMODITY RESOLUTION
      // ============================================
      $baseUrl = config('app.url_dev_admin');
      $varieties = Cache::remember('varieties_all_for_receipt', 3600, function () use ($baseUrl) {
          try {
              return Illuminate\Support\Facades\Http::timeout(5)
                  ->get($baseUrl . '/api/varieties')
                  ->json('data') ?? [];
          } catch (\Exception $e) {
              return [];
          }
      });

      $varietyCommodities = [];
      foreach ($varieties as $v) {
          if (!empty($v['id'])) {
              $varietyCommodities[$v['id']] = $v['commodity']['name'] ?? 'Pangan';
          }
      }

      // ============================================
      // STATUS LABEL LOGIC
      // ============================================
      $paidStatuses = ['paid', 'processing', 'pickup_ready', 'completed', 'shipped'];
      $currentStatus = $order['status'] ?? '';
      $isPaid = in_array($currentStatus, $paidStatuses);
      
      if ($isPaid) {
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
      
      // Payment details
      $paymentMethod = $payment['payment_method'] ?? $order['payment_type'] ?? 'Bank Transfer';
      if (empty($paymentMethod) || $paymentMethod === '-') {
          $paymentMethod = 'Bank Transfer';
      }
      
      $transactionId = $payment['transaction_id'] ?? $order['transaction_id'] ?? $order['order_code'] ?? '-';
      
      // ============================================
      // 🛠️ BUG FIX: HISTORICAL DATE PARSING
      // ============================================
      $rawDate = $order['created_at'] ?? $order['paid_at'] ?? $order['settlement_time'] ?? null;
      if ($rawDate) {
          try {
              $orderDate = \Carbon\Carbon::parse($rawDate)
                  ->setTimezone('Asia/Jakarta')
                  ->locale('id')
                  ->translatedFormat('d F Y');
          } catch (\Exception $e) {
              $orderDate = now('Asia/Jakarta')->translatedFormat('d F Y');
          }
      } else {
          $orderDate = now('Asia/Jakarta')->translatedFormat('d F Y');
      }

      $rawPaidDate = $order['paid_at'] ?? $order['settlement_time'] ?? null;
      if ($rawPaidDate) {
          try {
              $paidAt = \Carbon\Carbon::parse($rawPaidDate)
                  ->setTimezone('Asia/Jakarta')
                  ->locale('id')
                  ->translatedFormat('d F Y, H:i') . ' WIB';
          } catch (\Exception $e) {
              $paidAt = '-';
          }
      } else {
          $paidAt = '-';
      }
  @endphp

  <div class="container">
    <!-- Watermark Layer -->
    <div class="watermark-container">
        @if($isPaid)
            <div class="watermark-badge paid">LUNAS</div>
        @else
            <div class="watermark-badge unpaid">BELUM DIBAYAR</div>
        @endif
    </div>

    <!-- Official Header (Kop Surat) -->
    <div class="header-official">
        <div class="header-logo">
            <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo Kementan" />
        </div>
        <div class="header-text">
            <h1>Balai Besar Perakitan dan Modernisasi Bioteknologi dan Sumber Daya Genetik Pertanian</h1>
            <p class="address">Jl. Tentara Pelajar No.3A, RT.02/RW.7, Menteng, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16111</p>
        </div>
    </div>
    <div class="header-line"></div>

    <div class="doc-title">KUITANSI PEMBAYARAN</div>

    <!-- Transaction & Customer Info -->
    <table class="info-table">
        <tr>
            <td class="col-left">
                <span class="label">Nomor Kuitansi</span>: {{ $order['order_code'] ?? '-' }}<br>
                <span class="label">Tanggal Order</span>: {{ $orderDate }}<br>
                <span class="label">Metode Layanan</span>: {{ ($order['shipping_method'] ?? 'pickup') === 'pickup' ? 'Ambil di Tempat' : 'Kirim Kurir' }}
            </td>
            <td class="col-right">
                <span class="label">Nama Pembeli</span>: {{ $order['customer_name'] ?? '-' }}<br>
                <span class="label">Telepon</span>: {{ $order['customer_phone'] ?? '-' }}<br>
                <span class="label">Alamat</span>: {{ $order['customer_address'] ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- Payment Metadata -->
    <table class="info-table" style="margin-top: -15px; border-top: 1px dashed #eee; padding-top: 10px;">
        <tr>
            <td class="col-left">
                <span class="label">Metode Bayar</span>: {{ ucwords(str_replace('_', ' ', $paymentMethod)) }}<br>
                <span class="label">ID Transaksi</span>: {{ $transactionId }}<br>
                <span class="label">Tanggal Bayar</span>: {{ $paidAt }}
            </td>
            <td class="col-right">
                <span class="label">No. Resi PNBP</span>: {{ $payment['pnbp_receipt_no'] ?? $order['pnbp_receipt_no'] ?? '-' }}<br>
                <span class="label">Status</span>: 
                <span style="background:{{ $statusBgColor }}; color:{{ $statusTextColor }};" class="badge">
                    {{ $statusLabel }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Products Table -->
    <table class="items-table">
      <thead>
        <tr>
          <th width="5%">No.</th>
          <th width="50%">Detail Produk (Komoditas, Varietas, Kelas)</th>
          <th width="10%" class="text-center">Jumlah</th>
          <th width="15%" class="text-right">Harga Satuan</th>
          <th width="20%" class="text-right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach(($order['items'] ?? []) as $index => $it)
          @php
              $varietyId = $it['variety_id'] ?? null;
              $commodityName = $varietyCommodities[$varietyId] ?? 'Pangan';
          @endphp
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
              <span class="product-name">
                {{ $commodityName }} - {{ $it['resolved_variety_name'] ?? 'Varietas Tidak Diketahui' }}
              </span>
              <span class="product-meta">
                Kelas Benih: {{ $it['seed_class_code'] ?? $it['seed_class'] ?? 'N/A' }} | Lot: {{ $it['seed_lot_id'] ?? '-' }}
              </span>
            </td>
            <td class="text-center">
              {{ (int)($it['quantity'] ?? 0) }} kg
            </td>
            <td class="text-right">
              Rp {{ number_format((int)($it['unit_price'] ?? 0), 0, ',', '.') }}
            </td>
            <td class="text-right">
              Rp {{ number_format(
                ((int)($it['unit_price'] ?? 0)) * ((int)($it['quantity'] ?? 0)),
                0, ',', '.'
              ) }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Calculation Summary -->
    @php
        $subtotal = (int)($order['subtotal'] ?? 0);
        // Fallback calculation if subtotal is missing/zero but items exist
        if ($subtotal === 0 && !empty($order['items'])) {
            foreach($order['items'] as $item) {
                $subtotal += ((int)($item['unit_price']??0)) * ((int)($item['quantity']??0));
            }
        }
        
        $serviceFee = floor($subtotal * 0.01);
        $appFee = 4000;
        $shippingCost = (int)($order['shipping_cost'] ?? 0);
        $finalTotal = $subtotal + $serviceFee + $appFee + $shippingCost;
    @endphp

    <div class="summary-container clearfix">
        <table class="summary-table">
            <tr>
                <td class="label">Subtotal Barang</td>
                <td class="amount">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Biaya Layanan (1%)</td>
                <td class="amount">Rp {{ number_format($serviceFee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Biaya Aplikasi</td>
                <td class="amount">Rp {{ number_format($appFee, 0, ',', '.') }}</td>
            </tr>
            @if($shippingCost > 0)
            <tr>
                <td class="label">Biaya Pengiriman</td>
                <td class="amount">Rp {{ number_format($shippingCost, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="grand-total-row">
                <td class="label" style="color: #fff;">Total Pembayaran</td>
                <td class="amount" style="color: #fff;">Rp {{ number_format($finalTotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Decorative Bottom Line -->
    <div class="decorative-line clearfix"></div>

    <!-- Footer Validation & Verification -->
    <div class="footer-validation page-break-avoid">
        <div class="slogan">"Benih Berkualitas untuk Kedaulatan Pangan Bangsa"</div>
        <p style="margin: 8px 0 2px 0;">Dokumen ini dihasilkan secara otomatis oleh Sistem E-Commerce UPBS BRMP Biogen</p>
        
        <!-- Live scan-to-verify secure QR Code -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('order.detail', ['order_code' => $order['order_code'] ?? ''])) }}" alt="QR Verification" class="qr-image" />
        <div class="qr-text">Scan untuk verifikasi keaslian dokumen:<br>{{ route('order.detail', ['order_code' => $order['order_code'] ?? '']) }}</div>
    </div>

    <div class="foot">
      <button class="btn-print" onclick="window.print()">Cetak Resi Kuitansi</button>
    </div>
  </div>
</body>
</html>
