<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Webkul\Product\Repositories\ProductFlatRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SlugGenerator
{
    protected $productFlatRepository;

    public function __construct(ProductFlatRepository $productFlatRepository)
    {
        $this->productFlatRepository = $productFlatRepository;
    }

    /**
     * Tạo slug duy nhất từ tên sản phẩm
     *
     * @param string $name
     * @return string
     */
    public function generate(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $index = 1;

        while ($this->productFlatRepository->findOneByField('url_key', $slug)) {
            $slug = $baseSlug . '-' . $index;
            $index++;
        }
        return $slug;
    }
}
