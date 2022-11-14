<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDeviceIdToDeviceTokenOnAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'admins', function ( Blueprint $table ) {
            $table->renameColumn( 'device_id', 'device_token' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'admins', function ( Blueprint $table ) {
            $table->renameColumn( 'device_token', 'device_id' );
        } );
    }
}
