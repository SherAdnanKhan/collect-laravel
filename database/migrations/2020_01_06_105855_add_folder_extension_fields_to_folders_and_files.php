<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFolderExtensionFieldsToFoldersAndFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->boolean('hidden')->default(false)->index();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->boolean('hidden')->default(false)->index();
            $table->unsignedInteger('aliased_folder_id')->nullable()->after('folder_id');
            $table->foreign('aliased_folder_id')->references('id')->on('folders')
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
        Schema::table('folders', function (Blueprint $table) {
            $table->dropIndex(['hidden']);
            $table->dropColumn('hidden');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex(['hidden']);
            $table->dropColumn('hidden');
            $table->dropForeign('aliased_folder_id');
        });
    }
}
