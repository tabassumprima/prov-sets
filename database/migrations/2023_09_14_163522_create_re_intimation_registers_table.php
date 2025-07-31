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
        Schema::create('re_intimation_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->string('document_number')->nullable();
            $table->string('entry_no');
            $table->string('claim_year');
            $table->foreignId('product_code_id')->index()->nullable()->constrained();
            $table->foreignId('business_type_id')->index()->constrained();
            $table->string('document_reference');
            $table->string('policy_number');
            $table->dateTime('loss_date');
            $table->dateTime('intimation_date');
            $table->dateTime('system_date');
            $table->dateTime('system_posting_date');
            $table->string('final_tag')->nullable();
            $table->foreignId('re_products_treaty_id')->constrained();
            $table->string('pool_year')->nullable();
            $table->double('treaty_amount');
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
        Schema::dropIfExists('re_intimation_registers');
    }
};
