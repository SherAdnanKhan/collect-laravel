<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDefinedFieldsForRecordingTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recording_types', function(Blueprint $table) {
            $table->boolean('user_defined')->after('ddex_key');
            $table->index('user_defined');
        });

        Schema::table('recordings', function(Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedInteger('recording_type_id')->nullable()->after('subtitle');
            $table->foreign('recording_type_id')->references('id')->on('recording_types')
                ->onDelete('set null');
            $table->string('recording_type_user_defined_value')->after('recording_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recording_types', function(Blueprint $table) {
            $table->dropIndex(['user_defined']);
            $table->dropColumn('user_defined');
        });

        Schema::table('recordings', function(Blueprint $table) {
            $table->dropColumn('recording_type_user_defined_value');
            $table->string('type')->nullable()->after('subtitle');
            $table->dropForeign(['recording_type_id']);
            $table->dropColumn('recording_type_id');
        });
    }
}
