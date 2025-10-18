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
        Schema::create('import_settings', function (Blueprint $table) {
            $table->id();
            $table->string('channel_code')->unique(); // Mã kênh, ví dụ: default
            $table->string('locale_code')->nullable(); // Mã ngôn ngữ, ví dụ: en
            $table->string('currency_code')->nullable(); // Mã kênh, ví dụ: default
            $table->json('default_category_ids')->nullable();
            $table->string('meta_title_template')->nullable();
            $table->text('meta_description_template')->nullable();
            $table->text('meta_keywords_template')->nullable();
            $table->string('image_source')->default('url');
            $table->string('video_source')->default('url');
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_settings');
    }
};
