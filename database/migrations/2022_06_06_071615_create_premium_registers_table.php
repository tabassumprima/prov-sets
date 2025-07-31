<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePremiumRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->foreignId('insurance_type_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->string('policy_year');
            $table->string('policy_document');
            $table->string('party_code');
            $table->dateTime('issue_date');
            $table->dateTime('comm_date');
            $table->dateTime('sailing_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->double('policy_charges')->nullable();
            $table->double('total_premium')->nullable();
            $table->dateTime('system_posting_date');
            $table->foreignId('endorsement_type_id')->index()->nullable()->constrained();
            $table->double('gross_premium')->nullable();
            $table->double('gross_commission')->nullable();
            $table->double('admin_charge')->nullable();
            $table->double('gross_suminsured')->nullable();
            $table->string('policy_number')->nullable();
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
        Schema::dropIfExists('premium_registers');
    }
}
