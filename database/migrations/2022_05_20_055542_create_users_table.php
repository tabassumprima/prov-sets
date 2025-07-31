<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('organization_id')->index()->nullable()->constrained();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('google2fa_secret')->nullable();
            $table->boolean('google2fa_enable')->default(false)->nullable();
            $table->string('password');
            $table->string('otp')->nullable();
            $table->dateTime('is_otp_valid')->nullable();
            $table->boolean('is_otp_verified')->default(0);
            $table->string('verification_type')->default('email');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_first_login')->default(1);
            $table->json('extra')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
