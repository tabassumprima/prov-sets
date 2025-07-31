<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->index()->constrained();
            $table->string('description');
            $table->string('number');
            $table->string('branch_code')->nullable();
            $table->string('branch_abb')->nullable();
            $table->foreignId('business_type_id')->index()->constrained();
            $table->string('level_1')->nullable();
            $table->string('level1_desc')->nullable();
            $table->string('level_2')->nullable();
            $table->string('level2_desc')->nullable();
            $table->foreignId('import_detail_id')->index()->constrained();
            $table->foreignId('sub_import_id')->index()->nullable()->constrained();
            $table->unique(['organization_id', 'number']);
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
        Schema::dropIfExists('branches');
    }
}
