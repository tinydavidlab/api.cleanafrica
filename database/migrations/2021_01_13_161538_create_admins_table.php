<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'admins', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'company_id' );
            $table->string( 'name' );
            $table->string( 'phone_number' );
            $table->string( 'password' )->nullable();
            $table->string( 'type' )->default( 'collector' );
            $table->string( 'device_id' )->nullable();
            $table->string( 'activated_at' )->nullable();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'admins' );
    }
}
