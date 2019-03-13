<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterPartiesColumns extends Migration
{

    public function __construct()
    {
        // Workaround for Doctrine bug when altering columns in a table where an ENUM exists.
        // Even if the fields being modified aren't ENUM.
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parties', function(Blueprint $table) {
            $table->string('prefix')->nullable()->change();
            $table->string('suffix')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parties', function(Blueprint $table) {
            $table->string('prefix')->nullable(false)->change();
            $table->string('suffix')->nullable(false)->change();
        });
    }
}
