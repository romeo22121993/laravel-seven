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
            $table->string('nickname');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('company');
            $table->string('position');
            $table->string('custom_site');
            $table->string('user_phase');
            $table->string('article_disclosure');
            $table->string('email_disclosure');
            $table->string('facebook_link');
            $table->string('youtube_link');
            $table->string('linkedin_link');
            $table->string('twitter_link');
            $table->string('image');
            $table->string('user_role')->default('subscriber');
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
};
