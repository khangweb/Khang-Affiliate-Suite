<?php

namespace KhangWeb\Deeplink\Models;

use Illuminate\Database\Eloquent\Model;

class DeeplinkTemplate extends Model
{
    protected $table = 'deeplink_templates';

    protected $casts = [
        'accepted_domains' => 'array',
        'should_encode_url' => 'boolean',
        'apply_directly_to_product_url' => 'boolean',
        'status' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'base_url',
        'query_template',
        'should_encode_url',
        'apply_directly_to_product_url',
        'accepted_domains',
        'instructions',
        'status',
    ];
}
