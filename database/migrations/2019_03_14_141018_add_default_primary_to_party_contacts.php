<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultPrimaryToPartyContacts extends Migration
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
        Schema::table('party_contacts', function (Blueprint $table) {
            $table->boolean('primary')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('party_contacts', function (Blueprint $table) {
            $table->boolean('primary')->default(null)->change();
        });
    }
}
