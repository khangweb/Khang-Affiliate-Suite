<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Models\AttributeGroup;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\Log; // Import Log facade
use KhangWeb\Scraper\Models\ScrapedVariantPrice;

class AttributeService
{
    protected $attributeRepository;
    protected $inventoryService;
    protected $mediaService; // Inject MediaService
    protected $importSettingService ;

    public function __construct(
        AttributeRepository $attributeRepository,
        InventoryService $inventoryService,
        FlatSyncService $flatSyncService,
        MediaService $mediaService ,
        ImportSettingService $importSettingService 
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->mediaService = $mediaService;
        $this->importSettingService = $importSettingService;
    }

    /**
     * Processes configurable attributes, creating them if they don't exist, and adding options.
     *
     * @param \Webkul\Attribute\Models\AttributeFamily $attributeFamily
     * @param array $variantAttributesData
     * @param string $locale
     * @return array An array of configurable attribute codes and IDs
     */
    public function processConfigurableAttributes($attributeFamily, array $variantAttributesData, string $locale)
    {
        $configurableAttributeCodes = [];
        $configurableAttributeIds = [];

        // Tìm hoặc tạo nhóm thuộc tính 'General'

        $generalAttributeGroup = AttributeGroup::where('attribute_family_id', $attributeFamily->id)
                                                ->where('code', 'general')
                                                ->first();

        if (!$generalAttributeGroup) {
            $generalAttributeGroup = AttributeGroup::create([
                'attribute_family_id' => $attributeFamily->id,
                'name'                => 'General',
                'position'            => 1,
                'is_user_defined'     => 0,
                'code'                => 'general',
            ]);
        }
        $attributeGroupId = $generalAttributeGroup->id;

        foreach ($variantAttributesData as $attributeData) {
            $attributeCode = Str::slug($attributeData['attribute_name'], '_');
            $configurableAttributeCodes[] = $attributeCode;

            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            if (!$attribute) {
                $attribute = $this->attributeRepository->create([
                    'code' => $attributeCode,
                    'admin_name' => $attributeData['attribute_name'],
                    'type' => 'select',
                    'swatch_type' => null,
                    'validation' => null,
                    'position' => 10,
                    'is_required' => 0,
                    'is_unique' => 0,
                    'is_filterable' => 1,
                    'is_configurable' => 1,
                    'is_comparable' => 0,
                    'is_visible_on_front' => 0,
                    'is_user_defined' => 1,
                    'value_per_locale' => 0,
                    'value_per_channel' => 0,
                    'enable_wysiwyg'=>0,
                ]);
                // Gán vào nhóm General bằng bảng trung gian
                 $generalAttributeGroup->custom_attributes()->attach($attribute->id, ['position' => 10]);
                    
            } else {
                if (!$attribute->is_configurable || $attribute->attribute_group_id !== $attributeGroupId) {
                    $attribute->update([
                        'is_configurable' => 1,
                        'attribute_group_id' => $attributeGroupId, // Cập nhật để đảm bảo đúng nhóm
                    ]);
                } 
            }

            DB::table('attribute_translations')->updateOrInsert(
                ['attribute_id' => $attribute->id, 'locale' => $locale],
                ['name' => $attributeData['attribute_name']]
            );

            $configurableAttributeIds[] = $attribute->id;

            foreach ($attributeData['options'] as $optionData) {
                $optionName = $optionData['name'];

                $option = AttributeOption::where('attribute_id', $attribute->id)
                            ->whereHas('translations', function ($query) use ($optionName, $locale) {
                                $query->where('locale', $locale)
                                      ->where('label', $optionName);
                            })->first();

                if (!$option) {
                    $attribute->options()->create([
                        'admin_name' => $optionName,
                        'sort_order' => 0,
                        'swatch_value' => null,
                        $locale => ['label' => $optionName],
                    ]);
                } 
            }
        }
        return [$configurableAttributeCodes, $configurableAttributeIds];
    }

    public function getSuperAttributesData(array $configurableAttributeIds): array
    {
        $superAttributesData = [];

        foreach ($configurableAttributeIds as $attributeId) {
            $attribute = $this->attributeRepository->find($attributeId);

            if ($attribute) {
                // Lấy tất cả các ID tùy chọn cho thuộc tính này
                $attributeOptions = AttributeOption::where('attribute_id', $attribute->id)->pluck('id')->toArray();
                $superAttributesData[$attribute->code] = $attributeOptions;
            }
        }

        return $superAttributesData;
    }

    public function buildVariantsFromRaw(array $productData, $parent , $attributeFamilyId, array $categoryId, string $locale, $channel,$templateId ,$methodImage): array
    {

        $variants = [];
        $sku = '';
        $variantPrices = $productData['variantPrices'] ;
        foreach ($variantPrices as $index => $variant) {
            $optionsArray = [];
            $variantName = [] ;
            foreach ($variant['combination'] as $attribute) {
                $admin_name = $attribute['name'];
                $variantName[] = $admin_name;
                // $attribute_id = AttributeOption::where('admin_name', $admin_name)->first()->id ;
                $attributeCode = Str::slug($attribute['attribute'], '_');
                $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);
                $option = AttributeOption::where('attribute_id', $attribute->id)
                            ->whereHas('translations', function ($query) use ($admin_name, $locale) {
                                $query->where('locale', $locale)
                                      ->where('label', $admin_name);
                            })->first();

                $attribute_id =    $option->id ;         
                $optionsArray[] = $attribute_id ;
            }
            $options = implode('-', $optionsArray);
            $sku     = $parent->sku . '-variant-' . $options ;
            $variantProduct      = Product::where('sku' , $sku )->first();
            if($variant['price'] === null || $variant['price'] === 0 || $variant['price'] === ''){
                $price = $this->parsePriceToFloat($productData['price']) ;
            }else{
               
                $price = $this->parsePriceToFloat($variant['price']) ;
            }

            $qty = ($variant['inStock'] ?? false) ? 99 : 0;
            $imageUrl = $variant['image'] ?? null;
            $imagePath = null;
            // Xử lý hình ảnh cho biến thể
            $imageUrlToUse = $variant['image'] ?? ($productData['images'][0]['image'] ?? null);

            if ($imageUrlToUse) {
                $images = $this->mediaService->processImages($variantProduct, [$imageUrlToUse] , $methodImage);
            }

            $variantData = [
                'id'    => $variantProduct->id ,
                'sku'   => $sku ,
                'type' => 'simple',
                'name'  =>  $productData['name'] . '-' . implode(' ', array_values($variantName)),
                'categories' => $categoryId,
                'url_key'=> Str::slug($productData['name'] . '-' . implode(' ', array_values($variantName))) ,
                'price' => $price,
                'short_description' => $productData['short_description'] ?? '',
                'description' =>  $productData['description'] ?? '',
                'inventories' => [
                    1 => $qty,
                ],
                'images' => $images,
                'new' => 0,
                'featured' => 0,
                'status' => 1,
                'attribute_family_id' => $attributeFamilyId,
                'locale' => $locale,
                'channel' => $channel,
                'visible_individually' => 0,
            ];

            $variants[] = $variantData;
            ScrapedVariantPrice::create([
                'product_id' => $variantProduct->id,
                'parent_id'  => $parent->id ,
                'scraping_templates_id' => $templateId,
                'attribute_combination' => $variant['combination'],
                'price' => $price,
                'in_stock' =>  $variant['inStock']
            ]) ;
        }
       
        return $variants;
    }

    public function normalizeDataToAttributes(array $data): array
    {
        $attributes = [];

        // Lấy danh sách các attribute code tồn tại trong bảng `attributes`
        $attributeRepo = $this->attributeRepository;
        $allAttributeCodes = $attributeRepo->all()->pluck('code')->toArray();

        // Ánh xạ dữ liệu đầu vào sang attributes hợp lệ
        foreach ($data as $key => $value) {
            if (in_array($key, $allAttributeCodes)) {
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }

    public function parsePriceToFloat(string $priceString): float
    {
        $currencyCode = $this->importSettingService->getCurrencyCode();

        $currency = \Webkul\Core\Models\Currency::where('code', $currencyCode)->first();

        if (! $currency) {
            // fallback về USD nếu không tìm thấy
            $decimalSeparator = '.';
            $thousandSeparator = ',';
        } else {
            $decimalSeparator = $currency->decimal_separator ?? '.';
            $thousandSeparator = $currency->thousand_separator ?? ',';
        }

        // Bỏ symbol (tiền tệ) và khoảng trắng đầu/cuối
        $priceString = trim($priceString);
        $priceString = preg_replace('/[^\d' . preg_quote($decimalSeparator . $thousandSeparator, '/') . ']/u', '', $priceString);

        // Xóa dấu phân cách hàng nghìn
        if ($thousandSeparator !== '') {
            $priceString = str_replace($thousandSeparator, '', $priceString);
        }

        // Đổi dấu thập phân thành dấu chấm để cast float
        if ($decimalSeparator !== '.') {
            $priceString = str_replace($decimalSeparator, '.', $priceString);
        }

        return floatval($priceString);
    }


}