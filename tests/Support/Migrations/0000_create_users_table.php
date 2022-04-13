<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration{
  public function up(){
      Schema::create('users', function(Blueprint $table){
          $table->bigIncrements('id');
          $table->string('name');

          $table->timestamps();
      });
  }
};
