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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gl_code_id')->index()->nullable()->constrained();
            $table->string('category')->nullable();
            $table->foreignId('level_id')->index()->nullable()->constrained();
            $table->foreignId('organization_id')->index()->constrained();
            $table->string('type');
            $table->nestedSet();
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

        Schema::dropIfExists('chart_of_accounts');
    }
};
