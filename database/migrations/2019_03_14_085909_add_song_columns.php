<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSongColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedInteger('song_type_id')->nullable()->after('user_id');
            $table->foreign('song_type_id')->references('id')->on('song_types')
                ->onDelete('set null');

            $table->string('title_alt')->nullable()->after('subtitle');
            $table->string('subtitle_alt')->nullable()->after('title_alt');

            $table->date('created_on')->nullable()->after('subtitle_alt');

            $table->text('description')->nullable()->after('created_on');
            $table->text('lyrics')->nullable()->after('description');
            $table->text('notes')->nullable()->after('lyrics');

            $table->dropColumn('type');
            $table->dropColumn('genre');
            $table->dropColumn('artist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign('songs_song_type_id_foreign');

            $table->dropColumn('song_type_id');
            $table->dropColumn('title_alt');
            $table->dropColumn('subtitle_alt');
            $table->dropColumn('created_on');
            $table->dropColumn('description');
            $table->dropColumn('lyrics');
            $table->dropColumn('notes');

            $table->string('type')->nullable();
            $table->string('genre')->nullable();
            $table->string('artist');
        });
    }
}
