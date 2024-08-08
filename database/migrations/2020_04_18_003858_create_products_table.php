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
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
            $table->unique(['reference', 'sub_category_id'], 'indice_reference_sub_category');
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
