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
        Schema::create('client_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->unsigned()->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('subject');
            $table->text('message');
            $table->json('image_urls')->nullable();
            $table->json('video_urls')->nullable();
            $table->string('locale')->default('en'); // hoặc 'vi' tùy cấu hình mặc định
            $table->string('status')->default('pending');
            $table->longText('reply_content')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_messages');
    }
};
