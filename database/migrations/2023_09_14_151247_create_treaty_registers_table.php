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
        Schema::create('treaty_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('re_products_treaty_id')->index()->constrained();
            $table->foreignId('product_code_id')->index()->constrained();
            $table->foreignId('insurance_type_id')->index()->constrained();
            $table->foreignId('document_type_id')->index()->constrained();
            $table->string('document_number');
            $table->string('policy_year');
            $table->double('treaty_suminsured')->nullable();
            $table->double('treaty_premium')->nullable();
            $table->double('treaty_commission')->nullable();
            $table->string('treaty_pool_year')->nullable();
            $table->string('treaty_ratio_year')->nullable();
            $table->string('document_reference');
            $table->string('treaty_reference')->nullable();
            $table->dateTime('sailing_date')->nullable();
            $table->dateTime('system_posting_date')->nullable();
            $table->dateTime('re_date')->nullable();
            $table->string('re_quarter')->nullable();
            $table->foreignId('business_type_id')->constrained();
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
        Schema::dropIfExists('treaty_register');
    }
};
