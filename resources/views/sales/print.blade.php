<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Receipt #{{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .dashed { border-bottom: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 3px 0; }
        @media print {
            body { width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <div class="fw-bold" style="font-size: 16px;">{{ setting('store_name', 'SmartPOS Store') }}</div>
        <div>{{ setting('store_address', 'Main Store Address') }}</div>
        <div>Telp: {{ setting('store_phone', '-') }}</div>
    </div>

    <div class="dashed"></div>

    <div>
        <div>Inv: <span class="fw-bold">{{ $sale->invoice_number }}</span></div>
        <div>Date: {{ $sale->sale_date->format('Y-m-d H:i') }}</div>
        <div>Cashier: {{ $sale->user->name ?? 'Cashier' }}</div>
        <div>Customer: {{ $sale->customer->name ?? 'Walk-in' }}</div>
    </div>

    <div class="dashed"></div>

    <table>
        @foreach($sale->items as $item)
        <tr>
            <td colspan="2" class="fw-bold">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->unit_price, 2) }}</td>
            <td class="text-right fw-bold">{{ number_format($item->total_price, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="dashed"></div>

    <table>
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">{{ currency_format($sale->subtotal) }}</td>
        </tr>
        @if($sale->discount > 0)
        <tr>
            <td>Discount:</td>
            <td class="text-right">-{{ currency_format($sale->discount) }}</td>
        </tr>
        @endif
        @if($sale->tax > 0)
        <tr>
            <td>Tax:</td>
            <td class="text-right">{{ currency_format($sale->tax) }}</td>
        </tr>
        @endif
        <tr class="fw-bold">
            <td>TOTAL:</td>
            <td class="text-right" style="font-size: 15px;">{{ currency_format($sale->grand_total) }}</td>
        </tr>
        <tr>
            <td>Paid ({{ strtoupper($sale->payment_method) }}):</td>
            <td class="text-right">{{ currency_format($sale->paid_amount) }}</td>
        </tr>
        <tr>
            <td>Change:</td>
            <td class="text-right">{{ currency_format($sale->change_amount) }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <div class="text-center" style="margin-top: 15px;">
        <div>Thank You For Shopping!</div>
        <div style="font-size: 11px; margin-top: 4px;">Items purchased are non-refundable.</div>
    </div>
</body>
</html>
