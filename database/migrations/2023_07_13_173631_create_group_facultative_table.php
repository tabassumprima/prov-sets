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
        Schema::create('group_facultative', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->unsignedBigInteger('measurement_model_id');
            $table->unsignedBigInteger('cohorts_code_id');
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
        Schema::dropIfExists('group_facultative');
    }
};
