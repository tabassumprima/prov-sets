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
        Schema::create('fac_claim_paids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->string('document_number');
            $table->string('entry_no');
            $table->string('entry_no2');
            $table->foreignId('business_type_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->string('claim_year');
            $table->string('policy_number');
            $table->string('document_reference');
            $table->dateTime('payment_date');
            $table->dateTime('loss_date');
            $table->dateTime('intimation_date');
            $table->double('local_fac_amount');
            $table->double('foreign_fac_amount');
            $table->string('posting_tag');
            $table->dateTime('system_posting_date');
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
        Schema::dropIfExists('fac_claim_paids');
    }
};
