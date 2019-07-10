<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('STAUFF_Users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone_fixe');
            $table->string('telephone_portable')->nullable();
            $table->string('fonction');
            $table->string('service');
            $table->string('password', 60);
            $table->string('CardCode');
            $table->boolean('firstTime')->default(1);
            $table->boolean('superAdmin')->default(0);
            $table->boolean('admin')->default(0);
            $table->boolean('acces_panier')->default(0);
            $table->boolean('acces_demande')->default(0);
            $table->boolean('acces_prix')->default(0);
            $table->boolean('acces_suivi')->default(0);
            $table->boolean('acces_condition')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('STAUFF_Users');
    }
}
