<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->text('path')->nullable();
            $table->string('password')->nullable();
            $table->text('message')->nullable();
            $table->boolean('complete')->default(false)->index();
            $table->enum('status', ['new', 'live', 'cancelled', 'expired'])->default('new');
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shares');
    }
}
