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
        Schema::create('sub_vendor_all_company_categories', function (Blueprint $table) {
            $table->id();
            $table->string('_uid');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('category_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('sub_vendor_companies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('sub_vendor_company_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_vendor_company_categories');
    }
};
