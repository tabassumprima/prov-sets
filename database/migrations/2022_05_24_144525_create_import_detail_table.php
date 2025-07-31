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
        Schema::create('import_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index();
            $table->string('type');
            $table->string('identifier')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('message')->nullable();
            $table->foreignId('status_id')->index()->constrained();
            $table->unsignedBigInteger('run_by')->nullable();
            $table->tinyInteger('isLocked')->default(0);
            $table->tinyInteger('is_lambda_processed')->default(0);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->index('approved_by');
            $table->timestamps();
            $table->foreign('run_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_details');
    }
};
