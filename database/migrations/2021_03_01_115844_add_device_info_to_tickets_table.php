<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceInfoToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'tickets', function ( Blueprint $table ) {
            $table->string( 'unique_id' )->nullable();
            $table->string( 'user_agent' )->nullable();
            $table->string( 'model' )->nullable();
            $table->string( 'brand' )->nullable();
            $table->string( 'manufacturer' )->nullable();
            $table->string( 'app_version' )->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'tickets', function ( Blueprint $table ) {
            $table->dropColumn( [ 'unique_id', 'user_agent', 'model', 'brand', 'manufacturer', 'app_version' ] );
        } );
    }
}
