<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE UNIQUE INDEX lambda_name_org_unique ON lambda_functions (LOWER(name), organization_id)');
    }

    public function down()
    {
        DB::statement('DROP INDEX lambda_name_org_unique');
    }
};
