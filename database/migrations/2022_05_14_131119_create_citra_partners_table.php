<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitraPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citra_partners', function (Blueprint $table) {
            $table->tinyIncrements('id');

            $table->unsignedBigInteger('users_id');
            $table->unsignedTinyInteger('services_id');

            $table->unsignedInteger('price');

            $table->timestamp('active_at', $precision = 0)->nullable();

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
        Schema::dropIfExists('citra_partners');
    }
}
