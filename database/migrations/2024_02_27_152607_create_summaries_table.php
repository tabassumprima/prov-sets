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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->json('csv_summary')->nullable();
            $table->json('db_summary')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->index('approved_by')->nullable();
            $table->foreignId('import_detail_id')->index()->constrained();
            $table->string('path')->nullable();
            $table->foreignId('status_id')->index()->constrained();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summaries');
    }
};
