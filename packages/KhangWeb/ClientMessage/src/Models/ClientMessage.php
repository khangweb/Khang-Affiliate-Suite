<?php
namespace KhangWeb\ClientMessage\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMessage extends Model
{
    protected $table = 'client_messages';

    protected $fillable = [
        'request_id',
        'contact_person',
        'email',
        'gender',
        'subject',
        'message',
        'image_urls',
        'video_urls',
        'locale',
        'status',
        'reply_content',
        'token'
    ];

    protected $casts = [
        'image_urls' => 'array',
        'video_urls' => 'array',
    ];
}
