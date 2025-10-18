<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Illuminate\Support\Str;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Attribute\Repositories\AttributeRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log; // Đảm bảo dòng này có
use KhangWeb\Scraper\Models\ScrapedVariantPrice; // Nếu đây là model, đảm bảo namespace đúng
use KhangWeb\Scraper\Models\ProductUrl; // Đảm bảo namespace đúng cho ProductUrl của bạn
use Webkul\Product\Helpers\Indexers\Price as ProductPriceIndexer;

use Illuminate\Support\Arr;

class ProductBuilderService
{
    protected $productRepository;
    protected $attributeRepository;
    protected $mediaService;
    protected $flatSyncService;
    protected $slugGenerator;
    protected $inventoryService;
    protected $attributeService;
    protected $productPriceIndexer;

    public function __construct(
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository,
        MediaService $mediaService,
        FlatSyncService $flatSyncService,
        SlugGenerator $slugGenerator,
        InventoryService $inventoryService,
        AttributeService $attributeService,
        ProductPriceIndexer $productPriceIndexer
    ) {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->mediaService = $mediaService;
        $this->flatSyncService = $flatSyncService;
        $this->slugGenerator = $slugGenerator;
        $this->inventoryService = $inventoryService;
        $this->attributeService = $attributeService;
        $this->productPriceIndexer = $productPriceIndexer;
    }
    public function createSimpleProduct(array $productData, int $attributeFamilyId, array $categoryId, string $locale, string $channelCode, string $methodImage, string $methodVideo)
    {
  
        try {
            $productSlug = $this->slugGenerator->generate($productData['name']);
            $productSku = 'SCRAPED-' . uniqid();
            $productPrice = $this->attributeService->parsePriceToFloat($productData['price']);

            $data = [
                'type'                => 'simple',
                'sku'                 => $productSku,
                'attribute_family_id' => $attributeFamilyId,
                'weight'              => 0,
                'status'              => 1,
                'guest_checkout'      => 1,
                'new'                 => 0,
                'featured'            => 0,
                'visible_individually' => 1,
                'tax_category_id'     => null,
                'categories'          => $categoryId,
                'price'               => $productPrice,
                'url_key'             => $productSlug,
                'channel_id'          => $channelCode, // Sử dụng channelCode
                'name'                => $productData['name'],
                'short_description'   => $productData['short_description'] ?? '',
                'description'         => $productData['description'] ?? '',
                'locale'              => $locale,
                'channel'             => $channelCode, // Sử dụng channelCode
                'meta_title'          => $productData['meta_title'],
                'meta_description'    => $productData['meta_description'],
                'meta_keywords'       => $productData['meta_keywords'],
            ];

            $product = $this->productRepository->create($data);

            // Thêm Category
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            // Cập nhật tồn kho qua InventoryService
            $this->inventoryService->updateProductInventory($product->id, 100);

            // Tải và gắn hình ảnh
            $this->mediaService->processImages($product, $productData['images'] ?? [], $methodImage);

            // Tải và gắn videos
            if (!empty($productData['videos'])) {
                $this->mediaService->processVideo($product, $productData['videos'], $methodVideo);
            }

            // Đồng bộ ProductFlat
            $this->flatSyncService->syncProductFlat($product, [
                'sku'                 => $product->sku,
                'type'                => $product->type,
                'name'                => $productData['name'],
                'short_description'   => $productData['short_description'] ?? '',
                'description'         => $productData['description'] ?? '',
                'url_key'             => $productSlug,
                'new'                 => 0,
                'featured'            => 0,
                'status'              => 1,
                'price'               => $productPrice,
                'weight'              => 0,
                'locale'              => $locale,
                'channel'             => $channelCode, // Sử dụng channelCode
                'attribute_family_id' => $attributeFamilyId,
                'visible_individually' => 1,
                'meta_title'          => $productData['meta_title'],
                'meta_description'    => $productData['meta_description'],
                'meta_keywords'       => $productData['meta_keywords'],
            ]);

            // Thêm record vào 'product_attribute_value'
            $attributes = $this->attributeService->normalizeDataToAttributes($data);
            $product = $this->productRepository->update($data, $product->id, $attributes);

            // Thêm record vào 'product_urls' (model ProductUrl của bạn)
            ProductUrl::create([
                'product_id'  => $product->id,
                'link_source' => $productData['url'] ?? '',
                'link_aff'    => $productData['link_aff'] ?? ''
            ]);

            return $product;
        } catch (\Exception $e) {
            throw $e; // Re-throw the exception for higher-level handling (e.g., in the Job)
        }
    }
  public function createConfigurableProduct(
    array $productData,
    int $attributeFamilyId,
    array $categoryId,
    string $locale,
    string $channelCode,
    array $superAttributes,
    array $configurableAttributeCodes,
    int $templateId,
    string $methodImage,
    string $methodVideo
    ) {

        try {
            $productSlug = $this->slugGenerator->generate($productData['name']);
            $productSku  = 'SCRAPED-' . uniqid();
            $data = [
                'type'                 => 'configurable',
                'sku'                  => $productSku,
                'attribute_family_id'  => $attributeFamilyId,
                'weight'               => 0,
                'status'               => 1,
                'guest_checkout'       => 1,
                'new'                  => 0,
                'featured'             => 0,
                'visible_individually' => 1,
                'tax_category_id'      => null,
                'categories'           => $categoryId,
                'url_key'              => $productSlug,
                'name'                 => $productData['name'],
                'short_description'    => $productData['short_description'] ?? '',
                'description'          => $productData['description'] ?? '',
                'locale'               => $locale,
                'channel'              => $channelCode,
                'meta_title'           => $productData['meta_title'],
                'meta_description'     => $productData['meta_description'],
                'meta_keywords'        => $productData['meta_keywords'],
                'super_attributes'     => $superAttributes,
            ];

            $product = $this->productRepository->create($data);

            if (!empty($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            $this->mediaService->processImages($product, $productData['images'] ?? [], $methodImage);

            if (!empty($productData['videos'])) {
                $this->mediaService->processVideo($product, $productData['videos'], $methodVideo);
            }

            $variants = $this->attributeService->buildVariantsFromRaw(
                $productData,
                $product,
                $attributeFamilyId,
                $categoryId,
                $locale,
                $channelCode,
                $templateId,
                $methodImage
            );

            $keepIds = array_column($variants, 'id');
            Product::where('parent_id', $product->id)
                ->whereNotIn('id', $keepIds)
                ->delete();

            $this->updateVariants($productData, $variants, $templateId, $data['categories']);

            $product->refresh();

            $this->flatSyncService->syncProductFlat($product, [
                'sku'                  => $product->sku,
                'type'                 => $product->type,
                'name'                 => $productData['name'],
                'short_description'    => $productData['short_description'] ?? '' ,
                'description'          => $productData['description'] ?? '',
                'url_key'              => $productSlug,
                'new'                  => 0, 
                'featured'             => 0,
                'status'               => 1,
                'weight'               => 0,
                'locale'               => $locale,
                'channel'              => $channelCode,
                'attribute_family_id'  => $attributeFamilyId,
                'visible_individually' => 1,
                'meta_title'           => $productData['meta_title'],
                'meta_description'     => $productData['meta_description'],
                'meta_keywords'        => $productData['meta_keywords'],
            ]);

            $attributes = $this->attributeService->normalizeDataToAttributes($data);
            $product = $this->productRepository->update($data, $product->id, $attributes);

            $this->productPriceIndexer->reindexBatch(collect([$product]));

            ProductUrl::create([
                'product_id'  => $product->id,
                'link_source' => $productData['url'] ?? '',
                'link_aff'    => $productData['link_aff'] ?? ''
            ]);

            return $product;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function updateVariants(array $productData, array $variants, int $templateId, array $categories)
    {
        foreach ($variants as $variantData) {
            try {
                $attributes = $this->attributeService->normalizeDataToAttributes($variantData);
                $product = $this->productRepository->update($variantData, $variantData['id'], $attributes);

                if (!empty($categories)) {
                    $product->categories()->sync($categories);
                }

                $this->inventoryService->updateProductInventory($variantData['id'], $variantData['inventories'][1] ?? 0);

                $variantData['meta_title']       = $productData['meta_title'];
                $variantData['meta_description'] = $productData['meta_description'];
                $variantData['meta_keywords']    = $productData['meta_keywords'];

                $this->flatSyncService->syncProductFlat($product, $variantData);

            } catch (\Exception $e) {
                throw $e;
            }
        }

    }
}