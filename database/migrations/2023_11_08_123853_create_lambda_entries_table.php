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
        Schema::create('lambda_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('lambda_function_id')->index()->constrained();
            $table->foreignId('gl_code_id')->index()->nullable()->constrained();
            $table->integer('leg')->nullable();
            $table->foreignId('lambda_sub_function_id')->index()->constrained();
            $table->foreignId('level_id')->index()->nullable()->constrained();
            $table->string('transaction_type');
            $table->string('narration');
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
        Schema::dropIfExists('lambda_entries');
    }
};
