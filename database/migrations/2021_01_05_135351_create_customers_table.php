<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'customers', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
            $table->string( 'address' )->nullable();
            $table->string( 'type' )->default( 'customer' );
            $table->string( 'snoocode' )->nullable();
            $table->string( 'latitude' )->nullable();
            $table->string( 'longitude' )->nullable();
            $table->string( 'division' )->nullable();
            $table->string( 'subdivision' )->nullable();
            $table->string( 'country' )->nullable();
            $table->string( 'phone_number' )->nullable();
            $table->string( 'secondary_phone_number' )->nullable();
            $table->string( 'password' )->nullable();
            $table->string( 'property_photo' )->nullable();
            $table->string( 'device_id' )->nullable();
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
        Schema::dropIfExists( 'customers' );
    }
}
