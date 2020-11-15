<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('share_id');
            $table->string('email');
            $table->string('encrypted_email');
            $table->integer('download_count')->default(0);
            $table->timestamp('downloaded_last_at')->nullable();
            $table->timestamps();
            $table->foreign('share_id')->references('id')->on('shares')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_users');
    }
}
