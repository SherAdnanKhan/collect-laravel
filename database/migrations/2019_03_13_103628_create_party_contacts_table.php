<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties')
                ->onDelete('cascade');
            $table->enum('type', ['phone', 'email']);
            $table->index('type');
            $table->string('value');
            $table->boolean('primary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('party_contacts');
    }
}
