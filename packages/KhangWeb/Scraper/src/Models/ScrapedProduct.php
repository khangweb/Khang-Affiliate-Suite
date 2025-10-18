<?php
namespace KhangWeb\Scraper\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedProduct extends Model
{
    protected $table = 'scraped_products';

    protected $fillable = [
        'name','status','error_message','scraping_templates_id','ip', 'url', 'raw_data'
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];
}
