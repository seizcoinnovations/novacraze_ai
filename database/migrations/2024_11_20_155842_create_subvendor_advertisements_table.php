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
        Schema::create('subvendor_advertisements', function (Blueprint $table) {
            $table->id('_id');
            $table->string('_uid');
            $table->string('image')->nullable();
            $table->text('content');
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('subvendor_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('status');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subvendor_id')->references('id')->on('sub_vendors')->onDelete('cascade');
            $table->foreign('template_id')->references('_id')->on('subvendor_ad_templates_tables')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('sub_vendor_company_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subvendor_advertisements');
    }
};
