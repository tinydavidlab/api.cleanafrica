<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'replies', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'ticket_id' );
            $table->unsignedBigInteger( 'replyable_id' );
            $table->unsignedBigInteger( 'replyable_type' );
            $table->longText( 'content' );
            $table->string( 'photo' )->nullable();
            $table->timestamps();

            /* $table->foreign( 'ticket_id' )
                 ->references( 'id' )
                 ->on( 'tickets' );*/
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'replies' );
    }
}
