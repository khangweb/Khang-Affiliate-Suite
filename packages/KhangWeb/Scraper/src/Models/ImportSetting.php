<?php

namespace KhangWeb\Scraper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportSetting extends Model
{
    use HasFactory;

    protected $table = 'import_settings';

    protected $fillable = [
        'channel_code',
        'locale_code',
        'currency_code',
        'default_category_ids',
        'meta_title_template',      // Thêm vào
        'meta_description_template', // Thêm vào
        'meta_keywords_template',    // Thêm vào
        'image_source',
        'video_source'
    ];

    protected $casts = [
    'default_category_ids' => 'array',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

}