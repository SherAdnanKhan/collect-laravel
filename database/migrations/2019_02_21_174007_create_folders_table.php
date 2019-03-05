<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->unsignedInteger('folder_id')->nullable();
            $table->string('name');
            $table->unsignedInteger('depth')->default(0);
            $table->index('folder_id');
            $table->timestamps();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->unsignedInteger('folder_id')->nullable();
            $table->index('folder_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');

        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex('folder_id');
            $table->dropColumn('folder_id');
        });
    }
}
