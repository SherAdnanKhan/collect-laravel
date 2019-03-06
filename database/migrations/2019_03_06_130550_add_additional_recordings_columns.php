<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalRecordingsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->unsignedInteger('person_id')->nullable()->after('project_id');
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('set null');
            $table->unsignedInteger('song_id')->nullable()->after('person_id');
            $table->foreign('song_id')->references('id')->on('songs')
                ->onDelete('set null');
            $table->string('isrc')->after('description');
            $table->string('subtitle')->after('name');
            $table->string('version')->after('isrc');
            $table->date('recorded_on')->after('version');
            $table->date('mixed_on')->after('recorded_on');
            $table->integer('duration')->after('mixed_on');
            $table->string('language', 20)->after('duration');
            $table->string('key_signature')->after('language');
            $table->string('time_signature')->after('key_signature');
            $table->mediumInteger('tempo')->after('time_signature');
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
            $table->dropColumn('person_id');
            $table->dropColumn('song_id');
            $table->dropColumn('isrc');
            $table->dropColumn('subtitle');
            $table->dropColumn('version');
            $table->dropColumn('recorded_on');
            $table->dropColumn('mixed_on');
            $table->dropColumn('duration');
            $table->dropColumn('language');
            $table->dropColumn('key_signature');
            $table->dropColumn('time_signature');
            $table->dropColumn('tempo');
        });
    }
}
