<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->index()->constrained();
            $table->string('group_code')->nullable();
            $table->foreignId('portfolio_id')->index()->nullable()->constrained();
            $table->string('rei_portfolio')->nullable();
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
        Schema::dropIfExists('document_portfolios');
    }
}
