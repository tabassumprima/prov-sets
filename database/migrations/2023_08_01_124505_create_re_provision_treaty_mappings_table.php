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
        Schema::create('re_provision_treaty_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('re_products_treaty_id')->index()->constrained();
            $table->foreignId('provision_setting_id')->index()->constrained();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('risk_adjustments_id')->index()->constrained();
            $table->foreignId('ibnr_assumptions_id')->index()->constrained();
            $table->foreignId('discount_rates_id')->index()->constrained();
            $table->foreignId('claim_patterns_id')->index()->constrained();
            $table->string('expense_allocation');
            $table->string('earning_pattern');
            $table->double('re_recovery_ratio');
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
        Schema::dropIfExists('re_provision_treaty_mappings');
    }
};
