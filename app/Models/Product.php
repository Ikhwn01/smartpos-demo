<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'barcode',
        'name',
        'category_id',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'description',
        'status',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        }
        if ($this->stock <= $this->min_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        $codeSvg = 'assets/img/products/' . $this->product_code . '.svg';
        if (file_exists(public_path($codeSvg))) {
            return asset($codeSvg);
        }

        $categoryCode = $this->category ? $this->category->code : '';
        
        if (str_contains($categoryCode, 'ELEC') && file_exists(public_path('assets/img/products/elec.svg'))) {
            return asset('assets/img/products/elec.svg');
        }
        if (str_contains($categoryCode, 'FASH') && file_exists(public_path('assets/img/products/fash.svg'))) {
            return asset('assets/img/products/fash.svg');
        }
        if (str_contains($categoryCode, 'FOOD') && file_exists(public_path('assets/img/products/food.svg'))) {
            return asset('assets/img/products/food.svg');
        }
        if (str_contains($categoryCode, 'STAT') && file_exists(public_path('assets/img/products/stat.svg'))) {
            return asset('assets/img/products/stat.svg');
        }
        if (str_contains($categoryCode, 'HOME') && file_exists(public_path('assets/img/products/home.svg'))) {
            return asset('assets/img/products/home.svg');
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4f46e5&color=fff&size=200&font-size=0.33';
    }
}
