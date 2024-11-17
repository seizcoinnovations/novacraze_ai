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
        Schema::create('sub_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('_uid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username');
            $table->string('email');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->date('plan_start_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subscription_plan_id')->references('id')->on('sub_vendor_subscription_plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_vendors');
    }
};
