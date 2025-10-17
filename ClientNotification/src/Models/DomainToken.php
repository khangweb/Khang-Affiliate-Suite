<?php

namespace KhangWeb\ClientNotification\Models;

use Illuminate\Database\Eloquent\Model;

class DomainToken extends Model
{

    protected $table = 'domain_tokens';

    protected $fillable = [
        'customer_id',
        'access_token',
        'token_expires_at',
        'activation_token',
    ];

    protected $casts = [
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'token_expires_at' => 'datetime', // Convert sang Carbon
    ];
}
