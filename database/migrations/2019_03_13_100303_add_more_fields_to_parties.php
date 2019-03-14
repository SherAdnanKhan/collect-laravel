<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToParties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

        Schema::table('parties', function (Blueprint $table) {
            $table->string('isni')->nullable()->after('user_id');
            $table->enum('type', ['person', 'organisation', 'label'])->default('person')->after('isni');
            $table->index('isni');
            $table->index('type');
            $table->string('title')->default('')->after('type');
            $table->string('prefix')->default('')->after('title');
            $table->string('middle_name')->default('')->after('first_name');
            $table->string('last_name')->default('')->after('middle_name');
            $table->string('suffix')->default('')->after('last_name');
            $table->date('birth_date')->nullable()->after('suffix');
            $table->date('death_date')->nullable()->after('birth_date');
            $table->text('comments')->after('death_date');
            $table->dropColumn('email');
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
            $table->renameColumn('first_name', 'name');
            $table->dropIndex(['isni']);
            $table->dropIndex(['type']);
            $table->dropColumn('isni');
            $table->dropColumn('type');
            $table->dropColumn('title');
            $table->dropColumn('prefix');
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
            $table->dropColumn('suffix');
            $table->dropColumn('birth_date');
            $table->dropColumn('death_date');
            $table->dropColumn('comments');
            $table->string('email')->nullable();
        });
    }
}
