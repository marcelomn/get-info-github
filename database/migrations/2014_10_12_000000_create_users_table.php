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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            /*
             * Campos para login via oAuth (GitHub)
             */
            $table->enum('oauth_provider',['','github','facebook','google','twitter'])->default('');
            $table->string('oauth_uid', 50)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('location', 50)->nullable();
            $table->string('picture', 50)->nullable();
            $table->string('link', 50)->nullable();

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
};
