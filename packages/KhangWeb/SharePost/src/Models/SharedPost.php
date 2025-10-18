<?php

namespace KhangWeb\SharedPost\Models;

use Illuminate\Database\Eloquent\Model;

class SharedPost extends Model
{
    protected $fillable = [
        'slug', 'title', 'content',
        'meta_title', 'meta_description', 'meta_keywords',
        'featured_image'
    ];
}
