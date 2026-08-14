<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SaleService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create a new Sale transaction.
     */
    public function createSale(array $data, int $userId): Sale
    {
        return DB::transaction(function () use ($data, $userId) {
            $invoicePrefix = Setting::getByKey('invoice_prefix', 'INV');
            $invoiceNumber = $invoicePrefix . '-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock}");
                }

                $itemDiscount = floatval($item['discount'] ?? 0);
                $unitPrice = floatval($item['price'] ?? $product->selling_price);
                $itemTotal = ($unitPrice * $item['quantity']) - $itemDiscount;
                $subtotal += $itemTotal;

                $itemsData[] = [
                    'product' => $product,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'total_price' => $itemTotal,
                ];
            }

            $overallDiscount = floatval($data['discount'] ?? 0);
            $taxPercent = floatval($data['tax_percent'] ?? Setting::getByKey('tax', 0));
            $taxAmount = (($subtotal - $overallDiscount) * $taxPercent) / 100;
            $grandTotal = ($subtotal - $overallDiscount) + $taxAmount;

            $paidAmount = floatval($data['paid_amount']);
            $changeAmount = max(0, $paidAmount - $grandTotal);

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => $userId,
                'sale_date' => now(),
                'subtotal' => $subtotal,
                'discount' => $overallDiscount,
                'tax' => $taxAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($itemsData as $itemInfo) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemInfo['product']->id,
                    'product_name' => $itemInfo['product_name'],
                    'unit_price' => $itemInfo['unit_price'],
                    'quantity' => $itemInfo['quantity'],
                    'discount' => $itemInfo['discount'],
                    'total_price' => $itemInfo['total_price'],
                ]);

                // Reduce stock and log transaction
                $this->inventoryService->adjustStock(
                    $itemInfo['product'],
                    $itemInfo['quantity'],
                    'sale',
                    $userId,
                    $invoiceNumber,
                    "Sale Transaction #{$invoiceNumber}"
                );
            }

            Notification::createNotification(
                'New Sale Recorded',
                "Invoice #{$invoiceNumber} successfully created for amount " . number_format($grandTotal, 2),
                'sale',
                route('sales.show', $sale->id)
            );

            return $sale;
        });
    }
}
