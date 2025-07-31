<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_treaty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('re_products_treaty_id')->index()->constrained();
            $table->foreignId('measurement_model_id')->index()->constrained();
            $table->foreignId('cohorts_code_id')->index();
            $table->foreignId('group_id')->index()->constrained();
            $table->string('product_grouping');
            $table->string('onerous_threshold')->nullable();
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
        Schema::dropIfExists('group_treaty');
    }
};
