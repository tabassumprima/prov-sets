<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->foreignId('measurement_model_id')->index()->constrained();
            $table->unsignedBigInteger('cohorts_code_id');
            $table->foreignId('group_id')->index()->constrained();
            $table->string('product_grouping');
            $table->string('onerous_threshold')->nullable();
            $table->timestamps();

            $table->foreign('cohorts_code_id')->references('id')->on('cohorts')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_products');
    }
}
