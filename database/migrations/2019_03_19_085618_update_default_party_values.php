<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDefaultPartyValues extends Migration
{
    public function __construct()
    {
        // To fix the bug with changing columns on a table with an enum
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('middle_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('middle_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });
    }
}
