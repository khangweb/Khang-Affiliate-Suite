<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Models\ProductInventory;
use Illuminate\Support\Facades\Log; // Import Log facade

class InventoryService
{
    public function __construct()
    {
    }

    /**
     * Updates the inventory for a given product.
     *
     * @param int $productId
     * @param int $qty
     * @return void
     */
    public function updateProductInventory(int $productId, int $qty)
    {
        $inventorySource = InventorySource::first(); // Lấy inventory source đầu tiên

        if ($inventorySource) {
            ProductInventory::updateOrCreate(
                [
                    'product_id' => $productId,
                    'inventory_source_id' => $inventorySource->id,
                ],
                [
                    'qty' => $qty,
                ]
            );
        } 
    }
}