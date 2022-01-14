<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialLoginProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_providers', function (Blueprint $table) {
            $table->bigIncrements('social_provider_id');
            $table->string('social_provider_name');
            $table->string('social_provider_uuid');
            $table->bigInteger('social_provider_user_id')->unsigned();
            $table->string('social_provider_avatar')->nullable();
            $table->timestamps();

            $table->foreign('social_provider_user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
