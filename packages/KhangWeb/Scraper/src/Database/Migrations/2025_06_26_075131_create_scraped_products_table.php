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
        Schema::create('scraped_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Shopee, Lazada, Tiki, v.v.
             $table->string('status')->default('pending');
            $table->unsignedBigInteger('scraping_templates_id');
            $table->string('ip')->nullable(); // Shopee, Lazada, Tiki, v.v.
            $table->string('url')->unique();
            $table->json('raw_data'); // Lưu JSON toàn bộ kết quả scrape
            $table->text('error_message')->nullable();
            $table->boolean('is_imported')->default(false);
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraped_products');
    }
};
