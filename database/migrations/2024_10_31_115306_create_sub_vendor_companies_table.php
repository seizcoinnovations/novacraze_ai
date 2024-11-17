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
        Schema::create('sub_vendor_companies', function (Blueprint $table) {
            $table->id();
            $table->string('_uid');
            $table->unsignedBigInteger('subvendor_id');
            $table->string('name');
            $table->string('wa_number');
            $table->string('address');
            $table->string('city');
            $table->string('district');
            $table->string('state');
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::dropIfExists('sub_vendor_companies');
    }
};
