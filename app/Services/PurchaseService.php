<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create a Purchase record and update product stocks.
     */
    public function createPurchase(array $data, int $userId): Purchase
    {
        return DB::transaction(function () use ($data, $userId) {
            $purchaseNumber = 'PO-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $cost = floatval($item['purchase_price']);
                $qty = intval($item['quantity']);
                $totalPrice = $cost * $qty;
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'purchase_price' => $cost,
                    'total_price' => $totalPrice,
                ];
            }

            $discount = floatval($data['discount'] ?? 0);
            $tax = floatval($data['tax'] ?? 0);
            $totalAmount = ($subtotal - $discount) + $tax;

            $purchase = Purchase::create([
                'purchase_number' => $purchaseNumber,
                'supplier_id' => $data['supplier_id'],
                'user_id' => $userId,
                'purchase_date' => $data['purchase_date'] ?? now()->toDateString(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_status' => $data['payment_status'] ?? 'paid',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($itemsData as $itemInfo) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $itemInfo['product']->id,
                    'quantity' => $itemInfo['quantity'],
                    'purchase_price' => $itemInfo['purchase_price'],
                    'total_price' => $itemInfo['total_price'],
                ]);

                // Update product purchase price if updated
                $itemInfo['product']->update([
                    'purchase_price' => $itemInfo['purchase_price'],
                ]);

                // Increase stock and log transaction
                $this->inventoryService->adjustStock(
                    $itemInfo['product'],
                    $itemInfo['quantity'],
                    'purchase',
                    $userId,
                    $purchaseNumber,
                    "Purchase Order #{$purchaseNumber}"
                );
            }

            Notification::createNotification(
                'New Purchase Order Received',
                "Purchase order #{$purchaseNumber} created. Products received and stock updated.",
                'purchase',
                route('purchases.show', $purchase->id)
            );

            return $purchase;
        });
    }
}
