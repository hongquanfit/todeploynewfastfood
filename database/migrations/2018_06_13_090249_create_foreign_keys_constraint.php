<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeysConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table){
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('status_user_id')->references('id')->on('status_users');
        });
        Schema::table('foods', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('food_status_id')->references('id')->on('food_statuses');
        });
        Schema::table('food_nutritions', function(Blueprint $table){
            $table->foreign('food_id')->references('id')->on('foods');
            $table->foreign('nutrition_id')->references('id')->on('nutritions');
        });
        Schema::table('food_types', function(Blueprint $table){
            $table->foreign('food_id')->references('id')->on('foods');
            $table->foreign('type_id')->references('id')->on('types');
        });
        Schema::table('images', function(Blueprint $table){
            $table->foreign('food_id')->references('id')->on('foods');
        });
        Schema::table('comments', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('food_id')->references('id')->on('foods');
        });
        Schema::table('rates', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('food_id')->references('id')->on('foods');
        });
        Schema::table('address_foods', function(Blueprint $table){
            $table->foreign('food_id')->references('id')->on('foods');
            $table->foreign('address_id')->references('id')->on('addresses');
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
