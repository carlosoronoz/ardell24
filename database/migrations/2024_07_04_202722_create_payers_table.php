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
        Schema::create('payers', function (Blueprint $table) {
            $table->id();
            $table->string('type_passport', 180)->nullable();
            $table->string('passport', 180)->nullable();
            $table->string('name', 180)->nullable();
            $table->string('surname', 180)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('phone', 180)->nullable();
            $table->string('department', 180)->nullable();
            $table->string('location', 180)->nullable();
            $table->string('address', 180)->nullable();
            $table->unsignedBigInteger('sale_id');
            $table->foreign('sale_id')
                ->references('id')
                ->on('sales')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payers');
    }
};
