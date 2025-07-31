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
        Schema::create('provision_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->foreignId('provision_setting_id')->index()->constrained();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('risk_adjustments_id')->index()->constrained();
            $table->foreignId('ibnr_assumptions_id')->index()->constrained();
            $table->foreignId('discount_rates_id')->index()->constrained();
            $table->foreignId('claim_patterns_id')->index()->constrained();
            $table->double('expense_allocation');
            $table->string('earning_pattern');
            $table->double('ulr');
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
        Schema::dropIfExists('provision_mappings');
    }
};
