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
        Schema::create('product_urls', function (Blueprint $table) {
            $table->increments('id'); // ID tự tăng cho bảng product_urls

            // Thay đổi từ unsignedBigInteger sang unsignedInteger để khớp với kiểu dữ liệu của products.id
            $table->unsignedInteger('product_id');

            $table->string('link_source')->nullable();
            $table->string('link_aff')->nullable();
            $table->timestamps(); // created_at và updated_at

            // THÊM KHÓA NGOẠI ĐỂ LIÊN KẾT VỚI BẢNG PRODUCTS
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products') // Tên bảng mà khóa ngoại tham chiếu đến
                  ->onDelete('cascade'); // Tùy chọn: nếu sản phẩm bị xóa, các URL liên quan cũng bị xóa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_urls');
    }
};
