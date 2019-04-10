<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableColumnsOnSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->boolean('union_session')->default(0)->nullable()->change();
            $table->boolean('analog_session')->default(0)->nullable()->change();
            $table->boolean('drop_frame')->default(0)->nullable()->change();
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
            $table->text('description')->nullable(false)->change();
            $table->boolean('union_session')->default(0)->nullable(false)->change();
            $table->boolean('analog_session')->default(0)->nullable(false)->change();
            $table->boolean('drop_frame')->default(0)->nullable(false)->change();
        });
    }
}
