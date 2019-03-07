<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalSessionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('studio');

            $table->unsignedInteger('session_type_id')->nullable()->after('project_id');
            $table->foreign('session_type_id')->references('id')->on('session_types')
                ->onDelete('set null');
            $table->unsignedInteger('venue_id')->nullable()->after('session_type_id');
            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('set null');
            $table->dateTime('started_at')->nullable()->default(null)->after('description');
            $table->dateTime('ended_at')->nullable()->default(null)->after('started_at');
            $table->boolean('union_session')->default(0)->after('ended_at');
            $table->boolean('analog_session')->default(0)->after('union_session');
            $table->boolean('drop_frame')->default(0)->after('analog_session');
            $table->string('venue_room')->nullable()->after('drop_frame');
            $table->unsignedInteger('bitdepth')->nullable()->after('venue_room');
            $table->unsignedInteger('samplerate')->nullable()->after('bitdepth');
            $table->string('timecode_type')->nullable()->after('samplerate');
            $table->string('timecode_frame_rate')->nullable()->after('timecode_type');
            $table->index(['session_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('studio')->nullable()->after('name');
            $table->dropColumn('session_type_id');
            $table->dropColumn('venue_id');
            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');
            $table->dropColumn('union_session');
            $table->dropColumn('analog_session');
            $table->dropColumn('drop_frame');
            $table->dropColumn('venue_room');
            $table->dropColumn('bitdepth');
            $table->dropColumn('samplerate');
            $table->dropColumn('timecode_type');
            $table->dropColumn('timecode_frame_rate');
        });
    }
}
