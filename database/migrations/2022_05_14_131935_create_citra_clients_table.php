<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitraClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citra_clients', function (Blueprint $table) {
            $table->tinyIncrements('id');

            $table->bigInteger('users_id');
            $table->unsignedTinyInteger('services_id');
            $table->string('description', 64);

            $table->softDeletes();
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
        Schema::dropIfExists('citra_clients');
    }
}
