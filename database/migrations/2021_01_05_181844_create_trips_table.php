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
            $table->string( 'customer_name' );
            $table->string( 'customer_primary_phone_number' );
            $table->string( 'customer_secondary_phone_number' );
            $table->string( 'customer_apartment_number' );
            $table->string( 'customer_country' );
            $table->string( 'customer_division' );
            $table->string( 'customer_subdivision' );
            $table->string( 'customer_snoocode' );

            $table->string( 'delivery_status' );

            $table->string( 'collector_country' );
            $table->string( 'collector_division' );
            $table->string( 'collector_subdivision' );
            $table->string( 'collector_snoocode' );

            $table->string( 'photo_1' )->nullable();
            $table->string( 'photo_2' )->nullable();
            $table->string( 'bin_liner_quantity' )->nullable();

            $table->string( 'collector_date' );
            $table->string( 'collector_time' );
            $table->string( 'collector_signature' );
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
