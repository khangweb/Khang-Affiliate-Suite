<?php

namespace KhangWeb\Scraper\Services\ProductImport;

use KhangWeb\Scraper\Models\ImportSetting;

class ImportSettingService
{
    protected ?ImportSetting $importSetting; // Khai báo thuộc tính có thể là null

    public function __construct(ImportSetting $model)
    {
        // Khi service này được khởi tạo, nó sẽ cố gắng lấy record cài đặt đầu tiên.
        // Nếu không có record nào trong bảng, $this->importSetting sẽ là null.
        $this->importSetting = $model::first();
    }

    /**
     * Lấy toàn bộ đối tượng ImportSetting đã được tải.
     *
     * @return ImportSetting|null
     */
    public function getImport(): ?ImportSetting
    {
        return $this->importSetting;
    }

    /**
     * Lấy mã kênh mặc định.
     * Trả về 'default' nếu không có record hoặc giá trị không được thiết lập.
     *
     * @return string
     */
    public function getChannelCode(): string
    {
        return $this->importSetting->channel_code ?? 'default';
    }

    /**
     * Lấy mã locale mặc định.
     * Trả về 'en' nếu không có record hoặc giá trị không được thiết lập.
     *
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->importSetting->locale_code ?? 'en';
    }

    public function getCurrencyCode(): string
    {
        return $this->importSetting->currency_code ?? 'usd';
    }

      public function getImages(): string
    {
        return $this->importSetting->image_source ?? 'url';
    }

      public function getVideo(): string
    {
        return $this->importSetting->video_source ?? 'url';
    }

    /**
     * Lấy ID danh mục mặc định.
     * Trả về null nếu không có record hoặc giá trị không được thiết lập.
     *
     * @return int|null
     */
    public function getCategoryId(): array
    {
        return $this->importSetting->default_category_ids ?? [1];
    }

    /**
     * Lấy các mẫu meta tag.
     * Trả về các chuỗi rỗng nếu không có record hoặc mẫu không được thiết lập.
     *
     * @return array
     */
    public function getMetaTemplates(): array
    {
        return [
            'meta_title_template'       => $this->importSetting->meta_title_template ?? '',
            'meta_description_template' => $this->importSetting->meta_description_template ?? '',
            'meta_keywords_template'    => $this->importSetting->meta_keywords_template ?? '',
        ];
    }
}


