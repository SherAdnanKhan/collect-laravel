<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToCollaboratorPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborator_permissions', function (Blueprint $table) {
            $table->string('type')->default('project')->after('collaborator_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborator_permissions', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type')->default('project');
        });
    }
}
