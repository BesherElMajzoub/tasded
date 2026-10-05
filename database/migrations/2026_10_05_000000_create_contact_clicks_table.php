<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('ref', 6)->unique();
            $table->string('channel', 16);
            $table->string('variant', 50)->nullable();
            $table->string('page_path', 200)->nullable();
            $table->string('gclid', 200)->nullable()->index();
            $table->string('gbraid', 200)->nullable();
            $table->string('wbraid', 200)->nullable();
            $table->string('utm_source', 200)->nullable();
            $table->string('utm_medium', 200)->nullable();
            $table->string('utm_campaign', 200)->nullable();
            $table->string('utm_term', 200)->nullable();
            $table->string('utm_content', 200)->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_clicks');
    }
};
