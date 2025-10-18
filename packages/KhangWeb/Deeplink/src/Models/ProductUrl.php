<?php

namespace KhangWeb\Deeplink\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUrl extends Model
{
    protected $table = 'product_urls';

    protected $fillable = [
        'product_id',
        'link_source',
        'link_aff',
    ];
   
}
