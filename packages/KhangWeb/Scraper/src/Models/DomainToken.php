<?php

namespace KhangWeb\Scraper\Models;

use Illuminate\Database\Eloquent\Model;

class DomainToken extends Model
{
    protected $table = 'domain_tokens';

    protected $fillable = [
        'access_token',
        'token_expires_at',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];
}
