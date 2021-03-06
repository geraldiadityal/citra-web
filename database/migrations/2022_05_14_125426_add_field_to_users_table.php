<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 32)->change();
            $table->string('email', 80)->change();
            $table->string('password', 64)->change();
            $table->string('profile_photo_path', 100)->change();
            $table->string('roles', 7)->after('email')->default('USER');
            $table->string('phone_number', 16)->after('email')->nullable();
            $table->string('company_name', 64)->after('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('roles');
            $table->dropColumn('phone_number');
            $table->dropColumn('company_name');
        });
    }
}
