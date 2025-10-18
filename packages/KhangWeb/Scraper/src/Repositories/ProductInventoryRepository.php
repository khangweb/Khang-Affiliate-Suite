<?php

namespace KhangWeb\Scraper\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductInventoryRepository extends Repository
{
    public function model()
    {
        return \Webkul\Product\Models\ProductInventory::class;
    }
}
