<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioSystemDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_system_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('system_department_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('portfolio_system_department');
    }
}
