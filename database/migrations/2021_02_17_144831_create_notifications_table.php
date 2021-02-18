<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement( 'SET SESSION sql_require_primary_key=0' );
        Schema::create( 'notifications', function ( Blueprint $table ) {
            $table->uuid( 'id' )->primary();
            $table->string( 'type' );
            $table->morphs( 'notifiable' );
            $table->text( 'data' );
            $table->timestamp( 'read_at' )->nullable();
            $table->timestamps();
        } );
        DB::statement( 'SET SESSION sql_require_primary_key=1' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'notifications' );
    }
}
