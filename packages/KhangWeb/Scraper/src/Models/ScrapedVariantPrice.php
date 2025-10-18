<?php

namespace KhangWeb\Scraper\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedVariantPrice extends Model
{
    protected $table = 'scraped_variant_prices';

    protected $fillable = [
        'product_id',
        'parent_id',
        'scraping_templates_id',
        'attribute_combination',
        'price',
        'in_stock',
    ];

    protected $casts = [
        'attribute_combination' => 'array',
        'in_stock' => 'boolean',
    ];

    public $timestamps = true;
}
