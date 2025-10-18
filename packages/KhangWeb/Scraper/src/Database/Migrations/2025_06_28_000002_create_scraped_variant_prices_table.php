<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scraped_variant_prices', function (Blueprint $table) {
            $table->id();

            // ID của biến thể và sản phẩm cha trong hệ thống Bagisto
            $table->unsignedBigInteger('product_id');         // simple product
            $table->unsignedBigInteger('parent_id');  // configurable product

            // Template DOM tương ứng để xử lý clickSelector
            $table->unsignedBigInteger('scraping_templates_id')->nullable();

            // Dữ liệu tổ hợp biến thể
            $table->json('attribute_combination'); // [{"attribute": "Màu sắc", "name": "Đen"}]
            $table->string('price')->nullable();   // Giá hiển thị (có thể dạng chuỗi: "₫9.000 - ₫27.000")
            $table->boolean('in_stock')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_variant_prices');
    }
};
