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
        Schema::create('instant_offers', function (Blueprint $table) {
            $table->id('_id');
            $table->string('_uid');
            $table->unsignedBigInteger('subvendor_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->string('image')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('instant_offers');
    }
};
