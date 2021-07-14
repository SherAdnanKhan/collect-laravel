<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpiCaeColumnToPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->string('ipi_cae')->nullable()->after('isni');
            $table->index('ipi_cae');
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
            $table->dropIndex(['ipi_cae']);
            $table->dropColumn('ipi_cae');
        });
    }
}
