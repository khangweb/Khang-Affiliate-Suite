<?php

namespace KhangWeb\Scraper\Models; // Đảm bảo đúng namespace của bạn

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScrapingTemplate extends Model
{
    use HasFactory;

    protected $table = 'scraping_templates'; // Đảm bảo đúng tên bảng

    // Định nghĩa các trường có thể được gán hàng loạt (mass-assignable)
    protected $fillable = [
        'name',
        'fields',
        // Thêm các trường Attribute Fields mới
        'attributes_parent_selector', // Kiểu chuỗi trong DB
        'attributes_name_selector',   // Kiểu JSON trong DB
        'attributes_value_selector',  // Kiểu JSON trong DB
        'active_button',              // Kiểu JSON trong DB
        // 'variants', // <-- Xóa dòng này nếu bạn đã xóa cột 'variants' khỏi bảng 'scraping_templates'
    ];

    /**
     * Các thuộc tính nên được tự động cast (chuyển đổi) sang kiểu dữ liệu PHP.
     *
     * @var array
     */
    protected $casts = [
        'fields' => 'array', // Cast JSON string từ DB thành PHP array/object
        // Thêm cast cho các trường JSON mới
        'attributes_name_selector' => 'array',
        'attributes_value_selector' => 'array',
        'active_button' => 'array',
        // 'variants' => 'array', // <-- Xóa dòng này nếu bạn đã xóa cột 'variants' khỏi bảng
    ];
}