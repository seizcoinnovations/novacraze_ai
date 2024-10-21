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
        Schema::create('sub_vendor_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->integer('category_listing_count')->default(0);
            $table->integer('shop_count')->default(0);
            $table->integer('lead_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('booking_management_count')->default(0);
            $table->integer('instant_offer_count')->default(0);
            $table->integer('advertisement_count')->default(0);  
            $table->boolean('custom_bot')->default(0);
            $table->integer('plan_months_count')->default(0);
            $table->integer('plan_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_vendor_subscription_plans');
    }
};
