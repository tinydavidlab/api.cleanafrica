<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'feedback', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'company_id' );
            $table->unsignedBigInteger( 'customer_id' );
            $table->string( 'photo' )->nullable();
            $table->longText( 'message' );
            $table->timestamps();

            /* $table->foreign( 'customer_id' )
                 ->references( 'id' )
                 ->on( 'customers' );*/
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'feedback' );
    }
}
