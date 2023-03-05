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
            $table->bigIncrements('id');            
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('password');  
            $table->string('phone_number')->unique();            
            $table->timestamp('verified_at')->nullable();            
            $table->integer('is_token_verified')->default(0);
            $table->string('verify_token')->nullable();
            $table->string('address',191)->nullable(); 
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
        Schema::dropIfExists('users');
    }
}
