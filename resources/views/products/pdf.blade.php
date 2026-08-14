<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Catalog Report - {{ setting('store_name', 'SmartPOS') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #4f46e5; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 10px; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>{{ setting('store_name', 'SmartPOS Store') }}</h2>
        <p style="margin: 3px 0;">Product Catalog Inventory Summary</p>
        <small>Generated on: {{ date('Y-m-d H:i:s') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Barcode</th>
                <th>Product Name</th>
                <th>Category</th>
                <th class="text-right">Purchase Price</th>
                <th class="text-right">Selling Price</th>
                <th class="text-right">Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->product_code }}</td>
                <td>{{ $p->barcode ?: '-' }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category->name ?? '-' }}</td>
                <td class="text-right">{{ currency_format($p->purchase_price) }}</td>
                <td class="text-right">{{ currency_format($p->selling_price) }}</td>
                <td class="text-right">{{ $p->stock }} {{ $p->unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
