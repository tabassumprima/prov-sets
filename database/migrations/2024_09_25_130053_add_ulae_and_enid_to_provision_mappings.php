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
        Schema::table('provision_mappings', function (Blueprint $table) {
            // Add the columns with default values
            $table->double('ulae')->default(0);
            $table->double('enid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provision_mappings', function (Blueprint $table) {
            $table->dropColumn('ulae');
            $table->dropColumn('enid');
        });
    }
};
