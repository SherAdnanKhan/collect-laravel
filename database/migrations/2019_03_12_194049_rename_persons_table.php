<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('persons', 'parties');
        Schema::table('credits', function(Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->renameColumn('person_id', 'party_id');
            $table->foreign('party_id')->references('id')->on('parties')
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
        Schema::rename('parties', 'persons');
        Schema::table('credits', function(Blueprint $table) {
            $table->dropForeign(['party_id']);
            $table->renameColumn('party_id', 'person_id');
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('cascade');
        });
    }
}
