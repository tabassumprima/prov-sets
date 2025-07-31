<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->string('voucher_serial');
            $table->foreignId('system_department_id')->index()->constrained();
            $table->foreignId('gl_code_id')->index()->constrained();
            $table->string('policy_number')->nullable();
            $table->string('document_reference')->nullable();
            $table->double('transaction_amount')->nullable();
            $table->string('transaction_type')->nullable();
            $table->dateTime('system_posting_date')->nullable();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('voucher_type_id')->index()->constrained();
            $table->foreignId('accounting_year_id')->index()->constrained();
            $table->foreignId('business_type_id')->index()->constrained();
            $table->foreignId('entry_type_id')->index()->nullable()->constrained();
            $table->text('system_narration1')->nullable();
            $table->string('system_narration2')->nullable();
            $table->string('voucher_number');
            $table->string('unique_transaction');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('system_date')->nullable();
            $table->foreignId('import_detail_id')->index()->constrained();
            $table->foreignId('sub_import_id')->index()->nullable()->constrained();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
}
