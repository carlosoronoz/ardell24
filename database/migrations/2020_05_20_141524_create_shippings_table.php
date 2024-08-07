<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_shipping',11,2)->nullable();
            $table->string('type_shipping', 100)->nullable(); 
            $table->string('url_shipping', 180)->nullable();
            $table->string('courier', 180)->nullable();
            $table->string('tracker', 100)->nullable();
            $table->string('status_shipping', 100)->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->foreign('sale_id')
                ->references('id')
                ->on('sales')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}
