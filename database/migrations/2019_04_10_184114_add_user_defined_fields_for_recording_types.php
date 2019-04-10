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
        });
    }
}
