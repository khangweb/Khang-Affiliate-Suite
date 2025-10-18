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
        Schema::create('shared_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
        });
        
         // ✅ Thêm sẵn bài viết giới thiệu về KhangWeb
        DB::table('shared_posts')->insert([
            'slug' => 'about-khangweb',
            'title' => 'Giới thiệu về KhangWeb - Dịch vụ thiết kế website chuyên nghiệp',
            'content' =>"
                        <h2>Về KhangWeb</h2>
                        <p><strong>KhangWeb</strong> là đơn vị chuyên cung cấp dịch vụ <em>thiết kế website bán hàng, website doanh nghiệp</em> chuẩn SEO, tốc độ cao và dễ quản lý. Chúng tôi hướng tới mục tiêu giúp khách hàng kinh doanh hiệu quả hơn trên nền tảng số.</p>
                        
                        <h3>Tại sao chọn KhangWeb?</h3>
                        <ul>
                            <li>Thiết kế giao diện hiện đại, thân thiện với mọi thiết bị.</li>
                            <li>Tối ưu chuẩn SEO giúp website dễ dàng lên top Google.</li>
                            <li>Tích hợp sẵn tính năng quản lý sản phẩm, đơn hàng, khách hàng.</li>
                            <li>Hỗ trợ kỹ thuật nhanh chóng, tận tâm và lâu dài.</li>
                        </ul>
                        
                        <h3>Dịch vụ chính</h3>
                        <ul>
                            <li>Thiết kế website bán hàng chuyên nghiệp.</li>
                            <li>Thiết kế website doanh nghiệp, giới thiệu công ty.</li>
                            <li>Dịch vụ nâng cấp, bảo trì, SEO website.</li>
                        </ul>
                        
                        <p>Website được thiết kế bởi <strong><a href='https://khangweb.com' target='_blank' rel='noopener'>KhangWeb.com</a></strong>.</p>",

            'meta_title' => 'Giới thiệu về KhangWeb - Thiết kế website chuyên nghiệp',
            'meta_description' => 'KhangWeb cung cấp dịch vụ thiết kế website bán hàng, website doanh nghiệp chuẩn SEO, tốc độ cao, dễ quản lý. Được hơn 1000 khách hàng tin dùng.',
            'meta_keywords' => 'thiết kế website, thiết kế web bán hàng, khangweb, làm website chuyên nghiệp',
            'featured_image' => '/themes/velocity/assets/images/logo.svg', // ✅ Logo chuẩn theo giao diện Velocity
     
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_post');
    }
};
