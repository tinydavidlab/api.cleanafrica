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

            $table->unsignedBigInteger( 'company_id' );
            $table->string( 'customer_name' )->nullable();
            $table->string( 'customer_primary_phone_number' )->nullable();
            $table->string( 'customer_secondary_phone_number' )->nullable();
            $table->string( 'customer_apartment_number' )->nullable();
            $table->string( 'customer_country' )->nullable();
            $table->string( 'customer_division' )->nullable();
            $table->string( 'customer_subdivision' )->nullable();
            $table->string( 'customer_snoocode' )->nullable();
            $table->string( 'customer_latitude' )->nullable();
            $table->string( 'customer_longitude' )->nullable();
            $table->string( 'customer_latitude_number' )->default( '0' );
            $table->string( 'customer_longitude_number' )->default( '0' );

            $table->string( 'collector_name' )->nullable();
            $table->string( 'collector_country' )->nullable();
            $table->string( 'collector_division' )->nullable();
            $table->string( 'collector_subdivision' )->nullable();
            $table->string( 'collector_snoocode' )->nullable();
            $table->string( 'collector_date' )->nullable();
            $table->string( 'collector_time' )->nullable();
            $table->string( 'collector_signature' )->nullable();

            $table->string( 'delivery_status' )->default('pending');
            $table->string( 'bin_image' )->nullable();
            $table->string( 'property_image' )->nullable();
            $table->string( 'bin_liner_quantity' )->default(0);

            $table->string( 'notes' )->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign( 'company_id' )
                ->references( 'id' )
                ->on( 'trips' )
                ->cascadeOnDelete();
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
