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
        Schema::create('subvendor_bookings', function (Blueprint $table) {
            $table->id('_id');
            $table->string('_uid');
            $table->unsignedBigInteger('subvendor_id');
            $table->string('product');
            $table->date('booking_date');
            $table->text('comments');
            $table->integer('status');
            $table->string('wa_number');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subvendor_id')->references('id')->on('sub_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subvendor_bookings');
    }
};
