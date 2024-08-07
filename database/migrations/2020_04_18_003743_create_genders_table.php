<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->text('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['name', 'brand_id'], 'indice_reference_brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genders');
    }
}
