<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeeplinkTemplatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('deeplink_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_url')->nullable();
            $table->string('query_template')->nullable();
            $table->boolean('should_encode_url')->default(false);
            $table->boolean('apply_directly_to_product_url')->default(false);
            $table->json('accepted_domains');
            $table->text('instructions')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deeplink_templates');
    }
}
