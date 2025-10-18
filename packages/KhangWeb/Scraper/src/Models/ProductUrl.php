<?php
namespace KhangWeb\Scraper\Models;

use Illuminate\Database\Eloquent\Model;

class ProductURL extends Model
{
    protected $table = 'product_urls';

    protected $fillable = [
        'product_id','link_source','link_aff'
    ];

    
}
