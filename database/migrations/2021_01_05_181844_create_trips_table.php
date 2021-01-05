<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'trips', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'customer_name' );
            $table->string( 'customer_primary_phone_number' );
            $table->string( 'customer_secondary_phone_number' );
            $table->string( 'customer_apartment_number' );
            $table->string( 'customer_country' );
            $table->string( 'customer_division' );
            $table->string( 'customer_subdivision' );
            $table->string( 'customer_snoocode' );

            $table->string( 'delivery_status' );
            $table->timestamp( 'collection_date' );

            $table->timestamp( 'collector_country' );
            $table->timestamp( 'collector_division' );
            $table->timestamp( 'collector_subdivision' );
            $table->timestamp( 'collector_snoocode' );

            $table->timestamp( 'collector_date' );
            $table->timestamp( 'collector_time' );
            $table->timestamp( 'collector_signature' );
            $table->timestamps();

            $table->softDeletes();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'trips' );
    }
}
