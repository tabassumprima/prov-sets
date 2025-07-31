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
        Schema::create('journal_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('journal_entries_id')->index()->constrained();
            $table->foreignId('group_code_id')->index()->nullable()->constrained();
            $table->foreignId('treaty_group_code_id')->index()->nullable()->constrained();
            $table->foreignId('fac_group_code_id')->index()->nullable()->constrained();
            $table->foreignId('portfolio_id')->index()->constrained();
            $table->foreignId('import_detail_id')->index()->constrained();
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
        Schema::dropIfExists('journal_mappings');
    }
};
