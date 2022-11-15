<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'trucks', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'company_id' );
            $table->string( 'name' )->nullable();
            $table->string( 'license_number' )->nullable();
            $table->timestamps();

            /* $table->foreign( 'company_id' )
                 ->references( 'id' )
                 ->on( 'companies' );*/
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'trucks' );
    }
}
