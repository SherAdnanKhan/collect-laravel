<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectLabelAndArtistFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function(Blueprint $table) {
            $table->dropColumn('artist');
            $table->dropColumn('label');

            $table->unsignedInteger('main_artist_id')->nullable()->after('total_storage_used');
            $table->unsignedInteger('label_id')->nullable()->after('total_storage_used');

            $table->foreign('main_artist_id')->references('id')->on('parties')
                ->onDelete('set null');

            $table->foreign('label_id')->references('id')->on('parties')
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
        Schema::table('projects', function(Blueprint $table) {
            $table->string('artist');
            $table->string('label');
            $table->dropForeign(['main_artist_id']);
            $table->dropForeign(['label_id']);
            $table->dropColumn('label_id');
            $table->dropColumn('main_artist_id');
        });
    }
}
