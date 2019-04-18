<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableProjectIdOnFilesAndFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable()->change();
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable(false)->change();
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable(false)->change();
        });
    }
}
