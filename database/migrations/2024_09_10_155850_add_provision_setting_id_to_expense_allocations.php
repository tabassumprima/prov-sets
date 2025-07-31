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
        Schema::table('expense_allocations', function (Blueprint $table) {
            $table->foreignId('provision_setting_id')->index()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_allocations', function (Blueprint $table) {
            $table->dropForeign(['provision_setting_id']);
            $table->dropColumn('provision_setting_id');
        });
    }
};
