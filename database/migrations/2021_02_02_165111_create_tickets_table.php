<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'tickets', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'customer_id' );
            $table->unsignedBigInteger( 'admin_id' )->nullable();
            $table->string( 'subject' );
            $table->longText( 'content' );
            $table->string( 'photo' )->nullable();
            $table->enum( 'priority', TicketPriority::getValues() )->default( TicketPriority::LOW() );
            $table->enum( 'status', TicketStatus::getValues() )->default( TicketStatus::OPEN() );

            $table->timestamps();

           /* $table->foreign( 'customer_id' )
                ->references( 'id' )
                ->on( 'customers' );

            $table->foreign( 'admin_id' )
                ->references( 'id' )
                ->on( 'admins' );*/
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'tickets' );
    }
}
