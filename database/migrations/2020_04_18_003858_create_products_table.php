<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 100)->unique()->nullable();
            $table->string('name', 150)->nullable();
            $table->string('slug', 180)->unique();
            $table->string('stock', 30)->nullable();
            $table->decimal('essential_amount', 11, 2);
            $table->decimal('professional_amount', 11, 2);
            $table->string('discount', 20)->nullable();
            $table->enum('condition', ['General','Nuevo','Promoción']);
            $table->boolean('status')->default(true);
            $table->boolean('status_wa')->default(false);
            $table->text('detail')->nullable();
            $table->text('indication')->nullable();
            $table->text('tags')->nullable();
            $table->bigInteger('sales')->nullable();
            $table->json('images')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
            $table->unique(['reference', 'gender_id'], 'indice_reference_gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
