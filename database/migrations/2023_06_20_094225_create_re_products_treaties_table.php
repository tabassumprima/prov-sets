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
        Schema::create('re_products_treaties', function (Blueprint $table) {
            $table->id();
            $table->string('treaty_pool');
            $table->foreignId('organization_id')->index()->constrained();
            $table->foreignId('branch_id')->index()->constrained();
            $table->foreignId('system_department_id')->index()->constrained();
            $table->string('description');
            $table->string('treaty_type');
            $table->foreignId('business_type_id')->index()->constrained();
            $table->foreignId('import_detail_id')->index()->constrained();
            $table->foreignId('sub_import_id')->index()->nullable()->constrained();
            $table->unique(['organization_id', 'treaty_pool']);
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
        Schema::dropIfExists('re_products_treaties');
    }
};
