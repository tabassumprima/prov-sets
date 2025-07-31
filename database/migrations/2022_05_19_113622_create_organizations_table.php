<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('shortcode')->unique();
            $table->string('type');
            $table->string('sales_tax_number');
            $table->string('ntn_number');
            $table->longText('logo')->nullable();
            $table->foreignId('country_id')->index()->constrained();
            $table->foreignId('currency_id')->index()->constrained();
            $table->string('tenant_id')->unique(); //setting nullable to avoid errors. this should be remove on production
            $table->boolean('isBoarding')->default(0);
            $table->string('address');
            $table->foreignId('database_config_id')->index()->nullable()->constrained()->onDelete('set null');
            $table->string('date_format')->default('d M, Y');
            $table->string('financial_year')->default("");
            $table->string('agent_config')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
