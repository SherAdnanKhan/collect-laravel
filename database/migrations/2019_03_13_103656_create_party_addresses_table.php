<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartyAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties')
                ->onDelete('cascade');
            $table->string('line_1');
            $table->string('line_2')->nullable();
            $table->string('line_3')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->string('territory_code');
            $table->string('territory_code_type');
            $table->index(['city', 'postal_code']);
            $table->index(['territory_code', 'territory_code_type']);
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
        Schema::dropIfExists('party_addresses');
    }
}
