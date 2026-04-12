<!DOCTYPE html>
<html lang="ckb" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dream Bazaar - وەصڵی فرۆشتن</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    <!-- Add Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            background: #d1d5db;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            font-family: 'Tahoma', 'Segoe UI', Arial, sans-serif;
            padding: 20px;
        }

        .receipt-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .receipt {
            width: 105mm;
            min-height: 148mm;
            background: #f0eaea;
            margin: 0 auto;
            border-radius: 3px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
            page-break-after: always;
            display: flex;
            flex-direction: column;
        }

        .receipt:last-child {
            page-break-after: auto;
        }

        /* ── HEADER ── */
        .header {
            background: linear-gradient(135deg, #6b1929 0%, #7d1f30 40%, #6b1929 100%);
            padding: 15px 10px 15px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #b8973e;
            direction: ltr;
        }


        .qr-box {
            background: #fff;
            border: 1.5px solid #b8973e;
            border-radius: 5px;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2px;
        }

        .qr-scan-label {
            font-size: 5.5px;
            font-weight: 700;
            color: #6b1929;
            letter-spacing: 0.3px;
            text-align: center;
            margin-bottom: 2px;
        }

        .brand-center {
            flex: 1;
            text-align: center;
        }

        .brand-dream {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: 26px;
            font-weight: 700;
            color: #f0e0c8;
            line-height: 1;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            letter-spacing: 1px;
        }

        .brand-bazaar {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 10px;
            font-weight: 400;
            color: #ffffff;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .contact-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: flex-end;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #f0dfc8;
            font-size: 10px;
            direction: ltr;
            white-space: nowrap;
        }

        .contact-item .ci {
            color: #c9a84c;
            font-size: 8px;
        }

        /* Gold line */
        .gold-sep {
            height: 2px;
            background: linear-gradient(90deg, #6b1929, #b8973e, #e8d080, #b8973e, #6b1929);
        }

        /* ── BODY ── */
        .body {
            padding: 7px 7px 6px 7px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        /* Info white card */
        .info-card {
            background: #fff;
            border-radius: 10px;
            padding: 6px 12px;
            box-shadow: 0 1px 5px rgba(107, 25, 41, 0.08);
        }

        .info-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            padding: 3px 0;
            font-size: 10px;
            color: #1a1a1a;
            border-bottom: 0.5px solid #f0e8e8;
            direction: rtl;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .val {
            color: #111;
            font-weight: 600;
            font-size: 10px;
        }

        .info-row .ico {
            color: #7d1f30;
            font-size: 10px;
        }

        /* Products white card */
        .products-card {
            background: #fff;
            border-radius: 10px;
            padding: 6px 10px;
            box-shadow: 0 1px 5px rgba(107, 25, 41, 0.08);
        }

        .products-card table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            direction: rtl;
        }

        .products-card thead th {
            color: #666;
            font-weight: 700;
            font-size: 8.5px;
            padding: 2px 4px 5px;
            border-bottom: 1px solid #e8e0e0;
            text-align: right;
        }

        .products-card thead th.center {
            text-align: center;
        }

        .products-card thead th.left {
            text-align: left;
        }

        .products-card tbody td {
            padding: 4px 4px;
            color: #222;
            border-bottom: 0.5px solid #f5f0f0;
            font-size: 9px;
            text-align: right;
        }

        .products-card tbody td.center {
            text-align: center;
        }

        .products-card tbody td.left {
            text-align: left;
            font-family: monospace;
        }

        .products-card tbody tr:last-child td {
            border-bottom: none;
        }

        /* Summary maroon card */
        .summary-card {
            background: linear-gradient(150deg, #7d1f30 0%, #5c1520 100%);
            border-radius: 10px;
            padding: 8px 14px;
            box-shadow: 0 2px 8px rgba(107, 25, 41, 0.22);
            border: 1px solid #b8973e;
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #ffffff;
            padding: 5px 0;
            direction: rtl;
        }

        .sum-row .amt {
            font-family: monospace;
            font-size: 10px;
            font-weight: 800;
            color: #ffffff;
        }

        .sum-row.final-row {
            border-top: 1px solid rgba(184, 151, 62, 0.5);
            margin-top: 4px;
            padding-top: 6px;
            color: #ffffff;
            font-weight: 800;
            font-size: 12px;
        }

        .sum-row.final-row .amt {
            color: #ffffff;
            font-size: 13px;
            font-weight: 900;
        }

        /* Thank you */
        .thankyou {
            text-align: center;
            margin-top: auto;
            font-size: 12px;
            font-weight: 700;
            color: #3a1a20;
            padding: 6px 0 4px;
        }

        .thankyou .heart {
            color: #c0392b;
        }

        /* Footer bar — flush to bottom, no side margins */
        .footer-bar {
            background: linear-gradient(135deg, #6b1929 0%, #7d1f30 40%, #6b1929 100%);
            border-top: 2px solid #b8973e;
            height: 18px;
            width: 100%;
        }

        /* Print */
        @page {
            size: A6 portrait;
            margin: 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt {
                box-shadow: none;
                width: 105mm;
                min-height: 148mm;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        @foreach($invoices as $invoice)
            <div class="receipt">

                <!-- HEADER -->
                <div class="header">
                    <div class="qr-box">
                        <div class="qr-scan-label">♥ سکان بکە</div>
                        <svg width="36" height="36" viewBox="0 0 34 34" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="10" height="10" fill="none" stroke="#111" stroke-width="1.5" />
                            <rect x="3.5" y="3.5" width="5" height="5" fill="#111" />
                            <rect x="23" y="1" width="10" height="10" fill="none" stroke="#111" stroke-width="1.5" />
                            <rect x="25.5" y="3.5" width="5" height="5" fill="#111" />
                            <rect x="1" y="23" width="10" height="10" fill="none" stroke="#111" stroke-width="1.5" />
                            <rect x="3.5" y="25.5" width="5" height="5" fill="#111" />
                            <rect x="13" y="1" width="2" height="2" fill="#111" />
                            <rect x="16" y="1" width="2" height="2" fill="#111" />
                            <rect x="13" y="4" width="2" height="2" fill="#111" />
                            <rect x="16" y="7" width="2" height="2" fill="#111" />
                            <rect x="19" y="4" width="2" height="2" fill="#111" />
                            <rect x="19" y="10" width="2" height="2" fill="#111" />
                            <rect x="13" y="10" width="2" height="2" fill="#111" />
                            <rect x="13" y="13" width="2" height="2" fill="#111" />
                            <rect x="16" y="13" width="2" height="2" fill="#111" />
                            <rect x="19" y="13" width="2" height="2" fill="#111" />
                            <rect x="22" y="13" width="2" height="2" fill="#111" />
                            <rect x="25" y="13" width="2" height="2" fill="#111" />
                            <rect x="28" y="13" width="2" height="2" fill="#111" />
                            <rect x="31" y="13" width="2" height="2" fill="#111" />
                            <rect x="1" y="13" width="2" height="2" fill="#111" />
                            <rect x="4" y="13" width="2" height="2" fill="#111" />
                            <rect x="7" y="13" width="2" height="2" fill="#111" />
                            <rect x="10" y="13" width="2" height="2" fill="#111" />
                            <rect x="13" y="16" width="2" height="2" fill="#111" />
                            <rect x="19" y="16" width="2" height="2" fill="#111" />
                            <rect x="25" y="16" width="2" height="2" fill="#111" />
                            <rect x="13" y="19" width="2" height="2" fill="#111" />
                            <rect x="16" y="19" width="2" height="2" fill="#111" />
                            <rect x="22" y="19" width="2" height="2" fill="#111" />
                            <rect x="28" y="19" width="2" height="2" fill="#111" />
                            <rect x="31" y="19" width="2" height="2" fill="#111" />
                            <rect x="13" y="22" width="2" height="2" fill="#111" />
                            <rect x="19" y="22" width="2" height="2" fill="#111" />
                            <rect x="25" y="22" width="2" height="2" fill="#111" />
                            <rect x="13" y="25" width="2" height="2" fill="#111" />
                            <rect x="16" y="25" width="2" height="2" fill="#111" />
                            <rect x="22" y="25" width="2" height="2" fill="#111" />
                            <rect x="28" y="25" width="2" height="2" fill="#111" />
                            <rect x="13" y="28" width="2" height="2" fill="#111" />
                            <rect x="19" y="28" width="2" height="2" fill="#111" />
                            <rect x="25" y="28" width="2" height="2" fill="#111" />
                            <rect x="31" y="28" width="2" height="2" fill="#111" />
                            <rect x="13" y="31" width="2" height="2" fill="#111" />
                            <rect x="16" y="31" width="2" height="2" fill="#111" />
                            <rect x="22" y="31" width="2" height="2" fill="#111" />
                        </svg>
                    </div>

                    <div class="brand-center">
                        <div class="brand-dream">Dream</div>
                        <div class="brand-bazaar">Bazaar</div>
                    </div>

                    <div class="contact-col">
                        <div class="contact-item">07503815939 <span class="bi bi-telephone-fill"></span></div>
                        <div class="contact-item">07834646116 <span class="bi bi-telephone-fill"></span></div>
                        <div class="contact-item">dream_bazaar <span class="bi bi-instagram"></span></div>
                        <div class="contact-item">dream-bazaar <span class="bi bi-snapchat"></span></div>
                        <div class="contact-item">www.dreambazaar.com <span class="bi bi-globe"></span></div>
                    </div>
                </div>

                <div class="gold-sep"></div>

                <!-- BODY -->
                <div class="body">

                    <!-- Info card -->
                    <div class="info-card">
                        <div class="info-row">
                            <span class="ico"><i class="bi bi-calendar2-week-fill"></i></span>
                            <span
                                class="val">{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ico"><i class="bi bi-receipt"></i></span>
                            <span class="val">ژمارە: {{ $invoice->invoice_number ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ico"><i class="bi bi-person-fill"></i></span>
                            <span class="val">{{ $invoice->customer->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ico"><i class="bi bi-geo-alt-fill"></i></span>
                            <span class="val">{{ $invoice->customer->address ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ico"><i class="bi bi-telephone-fill"></i></span>
                            <span class="val"
                                style="direction:ltr;display:inline-block;">{{ $invoice->customer->phone ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Products card -->
                    <div class="products-card">
                        <table>
                            <thead>
                                <tr>
                                    <th>بەرهەم</th>
                                    <th class="center">دانە</th>
                                    <th class="left">نرخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($invoice->items && $invoice->items->count() > 0)
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->name ?? $item->link ?? '—' }}</td>
                                            <td class="center">{{ $item->quantity ?? $item->qty ?? 1 }}</td>
                                            <td class="left">{{ number_format($item->productprice ?? 0, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align:center;">هیچ بەرهەمێک نییە</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary card -->
                    <div class="summary-card">
                        <div class="sum-row">
                            <span>كوژمێ گشتی:</span>
                            <span
                                class="amt">{{ number_format($invoice->subtotal ?? $invoice->total_price ?? 0, 0) }}</span>
                        </div>
                        <div class="sum-row">
                            <span>گەهاندن:</span>
                            <span class="amt">{{ number_format($invoice->delivery_price ?? 0, 0) }}</span>
                        </div>
                        <div class="sum-row">
                            <span>داشکان:</span>
                            <span class="amt">{{ number_format($invoice->discount ?? 0, 0) }}</span>
                        </div>
                        <div class="sum-row final-row">
                            <span>كۆژمێ دوماهیێ:</span>
                            <span
                                class="amt">{{ number_format(($invoice->total_price ?? 0) - ($invoice->discount ?? 0) + ($invoice->delivery_fee ?? 0), 0) }}</span>
                        </div>
                    </div>

                    <!-- Thank you -->
                    <div class="thankyou">
                        سوپاس بۆ باوەڕیا هەوە <span class="heart">♥</span>
                    </div>

                </div>
                {{-- end .body --}}

                <!-- FOOTER BAR — flush bottom, no side margins -->
                <div class="footer-bar"></div>

            </div>
            {{-- end .receipt --}}
        @endforeach
    </div>
</body>

</html>