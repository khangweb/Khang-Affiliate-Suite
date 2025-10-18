<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Illuminate\Support\Facades\DB;
use KhangWeb\Scraper\Models\ScrapedProduct;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Models\Channel; // Import Channel model
use Webkul\Core\Models\Locale; // Import Locale model
use Illuminate\Support\Facades\Log; // Import Log facade

class ProductImporterService
{
    protected $attributeFamilyRepository;
    protected $categoryRepository;
    protected $attributeService;
    protected $productBuilderService;
    protected $importSettingService;
    protected $defaultAttributeFamilyId;
    protected $defaultCategoryId;
    protected $defaultChannel; // Thêm thuộc tính để lưu Channel mặc định
    protected $defaultLocaleCode; // Thêm thuộc tính để lưu mã locale mặc định
    protected $defaultImage; 
    protected $defaultVideo; 

    public function __construct(
        AttributeFamilyRepository $attributeFamilyRepository,
        CategoryRepository $categoryRepository,
        AttributeService $attributeService,
        ProductBuilderService $productBuilderService,
        ImportSettingService $importSettingService
    ) {

        $this->attributeFamilyRepository = $attributeFamilyRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeService = $attributeService;
        $this->productBuilderService = $productBuilderService;
        $this->importSettingService = $importSettingService;
        $this->findOrCreateDefaults();
    }
    /**
     * Finds or creates default attribute family, category, and channel.
     *
     * @return void
     */
    protected function findOrCreateDefaults()
    {
       // 1. Find or Create Default Attribute Family
        $this->defaultAttributeFamilyId = $this->attributeFamilyRepository->findOneWhere(['code' => 'default'])->id ?? $this->attributeFamilyRepository->create([
            'code' => 'default',
            'name' => 'Default',
        ])->id;
       // 2. Find or Create Default Category
       $this->defaultCategoryId =  $this->importSettingService->getCategoryId();     
       // 3. Find or Create Default Channel
       $this->defaultChannel = $this->importSettingService->getChannelCode(); 

       // Set the default locale code based on the default channel's default locale
       $this->defaultLocaleCode = $this->importSettingService->getLocaleCode(); 
       $this->defaultImage = $this->importSettingService->getImages(); 
       $this->defaultVideo = $this->importSettingService->getVideo(); 

    }
    /**
     * Imports a scraped product into Bagisto.
     *
     * @param ScrapedProduct $scrapedProduct
     * @return \Webkul\Product\Models\Product
     */
    public function import(ScrapedProduct $scrapedProduct)
    {
        return DB::transaction(function () use ($scrapedProduct) {
            $productData = $this->generateMetaTags($scrapedProduct->raw_data);
            
            $templateId = $scrapedProduct->scraping_templates_id ;
            $hasVariants = !empty($productData['variantPrices']) && !empty($productData['variantAttributes']);

            // Sử dụng channel và locale đã được xác định/tạo trong constructor
            $channel = $this->defaultChannel;
            $locale = $this->defaultLocaleCode;
            $methodImage = $this->defaultImage ;
            $methodVideo = $this->defaultVideo ;
            if ($hasVariants) {
                return $this->handleConfigurableProductImport($productData, $locale, $channel, $templateId , $methodImage , $methodVideo);
            } else {
                return $this->handleSimpleProductImport($productData, $locale, $channel ,  $methodImage , $methodVideo);
            }
        });
    }
    /**
     * Handles the import process for simple products.
     *
     * @param array $productData
     * @param string $locale
     * @param \Webkul\Core\Models\Channel $channel
     * @return \Webkul\Product\Models\Product
     */
    protected function handleSimpleProductImport(array $productData, string $locale, $channel ,$methodImage , $methodVideo)
    {
        return $this->productBuilderService->createSimpleProduct(
            $productData,
            $this->defaultAttributeFamilyId,
            $this->defaultCategoryId,
            $locale,
            $channel,
            $methodImage , $methodVideo
        );
    }
    /**
     * Handles the import process for configurable products and their variants.
     *
     * @param array $productData
     * @param string $locale
     * @param \Webkul\Core\Models\Channel $channel
     * @return \Webkul\Product\Models\Product
     */
    protected function handleConfigurableProductImport(array $productData, string $locale, $channel , $templateId ,$methodImage , $methodVideo)
    {
        // Xử lý các thuộc tính có thể cấu hình và lấy ID của chúng
        [$configurableAttributeCodes, $configurableAttributeIds] = $this->attributeService->processConfigurableAttributes(
           $this->attributeFamilyRepository->find($this->defaultAttributeFamilyId), // Truyền AttributeFamily object
            $productData['variantAttributes'],
            $locale
        );

        $superAttributes =  $this->attributeService->getSuperAttributesData($configurableAttributeIds) ;

        // Tạo sản phẩm configurable (sản phẩm cha)
        $parentProduct = $this->productBuilderService->createConfigurableProduct(
            $productData,
            $this->defaultAttributeFamilyId,
            $this->defaultCategoryId,
            $locale,
            $channel,
            $superAttributes,
            $configurableAttributeCodes ,// Truyền configurable attribute IDs vào để gán super_attributes
            $templateId,
            $methodImage , $methodVideo
        );        
        return $parentProduct;
    }

         /**
     * Generate meta tags based on templates and product data.
     *
     * @param array $productData
     * @return array
     */
    protected function generateMetaTags(array $productData): array
    {
        $placeholders = [
            '{product_name}'    => $productData['name'] ?? '',
            '{short_description}' => $productData['short_description'] ?? '',
            '{brand_name}'      => $productData['brand'] ?? '',
        ];

        // Lấy tên của default category
        $lastValue = end($this->defaultCategoryId);
        $defaultCategory = $this->categoryRepository->find($lastValue);
        $placeholders['{category_name}'] = $defaultCategory ? $defaultCategory->name : '';
        $template = $this->importSettingService->getMetaTemplates();
        // Áp dụng các mẫu meta từ thuộc tính metaTemplates
        $productData['meta_title'] = $this->applyTemplate($template['meta_title_template'] ?? '', $placeholders);
        $productData['meta_description'] = $this->applyTemplate($template['meta_description_template'] ?? '', $placeholders);
        $productData['meta_keywords'] = $this->applyTemplate($template['meta_keywords_template'] ?? '', $placeholders);

        return $productData;
    }

    protected function applyTemplate(string $template, array $placeholders): string
    {
        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }
}