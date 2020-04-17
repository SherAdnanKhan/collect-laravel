<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->text('path')->nullable();
            $table->boolean('complete')->default(false)->index();
            $table->timestamp('expires_at', 0)->nullable()->index();
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
        Schema::dropIfExists('download_jobs');
    }
}
