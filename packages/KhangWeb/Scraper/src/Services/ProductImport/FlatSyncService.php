<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Webkul\Product\Models\ProductFlat;

use Illuminate\Support\Facades\Log;

class FlatSyncService
{
    public function __construct( ) {

    }

    /**
     * Đồng bộ dữ liệu sản phẩm vào bảng product_flat
     *
     * @param \Webkul\Product\Contracts\Product $product
     * @param string $channel
     * @param string $locale
     * @return void
     */
 public function syncProductFlat($product, array $productFlatData)
    {
        $result = ProductFlat::updateOrCreate(
            [
                'product_id'    => $product->id,
                'locale'        => $productFlatData['locale'],
                'channel'       => $productFlatData['channel'],
            ],
            $productFlatData
        );
    }
}
