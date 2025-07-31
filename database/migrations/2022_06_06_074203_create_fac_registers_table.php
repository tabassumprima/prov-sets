<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fac_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('insurance_type_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->string('document_number');
            $table->string('document_serial')->nullable();
            $table->string('fac_year');
            $table->string('policy_number')->nullable();
            $table->string('policy_document')->nullable();
            $table->double('fac_suminsured')->nullable();
            $table->double('fac_premium')->nullable();
            $table->double('fac_commission')->nullable();
            $table->string('localforeign_tag')->nullable();
            $table->dateTime('issue_date');
            $table->string('reinsurer')->nullable();
            $table->dateTime('comm_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('ri_document_number');
            $table->dateTime('system_posting_date');
            $table->dateTime('sailing_date')->nullable();
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
        Schema::dropIfExists('fac_registers');
    }
}
