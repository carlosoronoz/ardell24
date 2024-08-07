<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('business_name',150)->nullable();
            $table->string('email',150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address',150)->nullable();
            $table->text('logo')->nullable();
            $table->string('instagram',150)->nullable();
            $table->string('credential',180)->nullable();
            $table->string('integrator_id',180)->default('dev_3473303deb0c11eb92c30242ac130004');
            $table->text('access_token_whatsapp')->nullable();
            $table->string('mobile_id',180)->nullable();
            $table->string('business_id',180)->nullable();
            $table->string('catalog_id',180)->nullable();
            $table->string('wa_business_id',180)->nullable();
            $table->string('graph_version',180)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('production_mode')->default(true);
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
        Schema::dropIfExists('companies');
    }
}
