<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('type_document', 100)->nullable();
            $table->string('num_document', 30)->unique();
            $table->text('notes')->nullable();            
            $table->string('type_operation', 100)->nullable();
            $table->string('num_transaction', 100)->nullable();
            $table->string('preference_id', 100)->nullable();
            $table->text('preference_url')->nullable();
            $table->string('payment_id', 100)->nullable();
            $table->dateTime('date_document')->nullable();
            $table->decimal('total_amount', 11, 2)->nullable();
            $table->enum('state', ['Anulado','Pendiente','En Proceso','Aprobado']);
            $table->boolean('status')->default(true);
            $table->json('notification')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
