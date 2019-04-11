<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ddex_key');
            $table->boolean('user_defined');
        });

        Schema::table('recordings', function(Blueprint $table) {
            $table->dropColumn('version');
            $table->unsignedInteger('version_type_id')->nullable()->after('isrc');
            $table->foreign('version_type_id')->references('id')->on('version_types')
                ->onDelete('set null');
            $table->string('version_type_user_defined_value')->after('version_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('version_types');

        Schema::table('recordings', function(Blueprint $table) {
            $table->dropColumn('version_type_user_defined_value');
            $table->string('version')->nullable()->after('isrc');
            $table->dropForeign(['version_type_id']);
            $table->dropColumn('version_type_id');
        });
    }
}
