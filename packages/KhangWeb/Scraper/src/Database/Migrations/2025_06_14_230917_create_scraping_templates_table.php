<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scraping_templates', function (Blueprint $table) {
            $table->increments('id'); // ID tự tăng
            $table->string('name')->unique(); // Tên template, bắt buộc và duy nhất
            $table->json('fields'); // Mảng các trường sản phẩm, lưu dưới dạng JSON

            // Các trường Attribute mới (từ frontend)
            // Sử dụng snake_case cho tên cột trong database
            $table->string('attributes_parent_selector')->nullable(); // Selector cha của attributes (chuỗi)
            $table->json('attributes_name_selector')->nullable();    // Object chứa selector và extractType cho tên thuộc tính
            $table->json('attributes_value_selector')->nullable();   // Object chứa selector, extractType và thumbnail cho giá trị thuộc tính
            $table->json('active_button')->nullable();               // Object chứa selector và status cho active button

            // Nếu bạn không còn sử dụng trường 'variants' theo cấu trúc cũ, hãy loại bỏ nó.
            // Nếu bạn vẫn muốn giữ nó cho mục đích khác, hãy thêm lại dòng sau:
            // $table->json('variants')->nullable();

            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraping_templates');
    }
};
