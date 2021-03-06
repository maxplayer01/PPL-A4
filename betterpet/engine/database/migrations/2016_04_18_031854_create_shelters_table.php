<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shelterName');
			$table->longText('address');
			$table->longText('description');
			$table->string('picture',255);
            $table->integer('domicile')->index();
            $table->integer('rating');
            $table->integer('numRating');
            $table->integer('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
