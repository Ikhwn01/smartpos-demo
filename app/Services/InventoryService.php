<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Notification;
use App\Models\Product;

class InventoryService
{
    /**
     * Adjust product stock manually or via transactions.
     */
    public function adjustStock(Product $product, int $quantity, string $type, ?int $userId = null, ?string $referenceNumber = null, ?string $notes = null): Product
    {
        $oldStock = $product->stock;
        
        if ($type === 'in' || $type === 'purchase') {
            $newStock = $oldStock + $quantity;
        } elseif ($type === 'out' || $type === 'sale') {
            $newStock = max(0, $oldStock - $quantity);
        } elseif ($type === 'adjustment') {
            $newStock = max(0, $quantity); // quantity represents target stock value in explicit adjustment
            $quantity = abs($newStock - $oldStock);
        } else {
            $newStock = $oldStock;
        }

        $product->update(['stock' => $newStock]);

        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'reference_number' => $referenceNumber,
            'notes' => $notes,
        ]);

        $this->checkLowStockNotification($product);

        return $product;
    }

    /**
     * Check if product stock triggers notification.
     */
    public function checkLowStockNotification(Product $product): void
    {
        if ($product->stock <= 0) {
            Notification::createNotification(
                'Out of Stock Alert',
                "Product '{$product->name}' (Code: {$product->product_code}) is completely out of stock!",
                'out_of_stock',
                route('products.show', $product->id)
            );
        } elseif ($product->stock <= $product->min_stock) {
            Notification::createNotification(
                'Low Stock Warning',
                "Product '{$product->name}' is running low on stock ({$product->stock} {$product->unit} remaining).",
                'low_stock',
                route('products.show', $product->id)
            );
        }
    }
}
