<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRootFolderIdColumnToFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedInteger('root_folder_id')->nullable();
            $table->foreign('root_folder_id')->references('id')->on('folders')
                ->onDelete('SET NULL');

            $table->index(['root_folder_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropForeignKey(['root_folder_id']);
            $table->dropColumn('root_folder_id');
        });
    }
}
