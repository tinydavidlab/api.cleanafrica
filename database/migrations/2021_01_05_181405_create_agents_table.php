<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'agents', function ( Blueprint $table ) {
            $table->id();
            $table->bigInteger( 'company_id' )->unsigned();
            $table->string( 'type' )->default( 'waste_collector' );
            $table->string( 'name' );
            $table->string( 'phone_number' );
            $table->timestamp( 'activated_at' )->nullable();
            $table->timestamps();

            $table->foreign( 'company_id' )
                ->references( 'id' )
                ->on( 'companies' )
                ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'agents' );
    }
}
