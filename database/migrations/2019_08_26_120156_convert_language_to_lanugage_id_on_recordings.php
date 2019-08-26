<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertLanguageToLanugageIdOnRecordings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->renameColumn('language', 'language_id');
        });

        DB::statement('UPDATE recordings r INNER JOIN languages l on l.name = r.language_id SET r.language_id = l.id');

        Schema::table('recordings', function (Blueprint $table) {
            $table->unsignedInteger('language_id')->nullable()->default(null)->change();
            $table->foreign('language_id')->references('id')->on('languages')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
        });

        DB::statement('UPDATE recordings r INNER JOIN languages l on l.id = r.language_id SET r.language_id = l.name');

        Schema::table('recordings', function (Blueprint $table) {
            $table->renameColumn('language_id', 'language');
            $table->string('language', 20)->nullable()->change();
        });
    }
}
