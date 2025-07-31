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
        Schema::create('claim_paid_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('business_type_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->nullable()->constrained();
            $table->string('entry_no');
            $table->double('sales_tax')->nullable();
            $table->dateTime('intimation_date');
            $table->dateTime('loss_date');
            $table->string('document_reference');
            $table->string('policy_number');
            $table->dateTime('system_posting_date');
            $table->double('claim_amount')->nullable();
            $table->dateTime('payment_date');
            $table->foreignId('import_detail_id')->index()->constrained();
            $table->foreignId('sub_import_id')->index()->nullable()->constrained();
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
        Schema::dropIfExists('claim_paid_registers');
    }
};
