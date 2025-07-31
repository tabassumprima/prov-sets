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
        Schema::table('fac_group_mappings', function (Blueprint $table) {
            $table->renameColumn('policy_number', 'group_reference');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fac_group_mappings', function (Blueprint $table) {
            $table->renameColumn('group_reference', 'policy_number');
        });
    }
};
