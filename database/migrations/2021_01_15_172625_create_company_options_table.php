<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'company_options', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'company_id' );
            $table->string( 'name' );
            $table->string( 'value' );
            $table->string( 'type' )->default( true );
            $table->timestamps();

            $table->foreign( 'company_id' )
                ->references( 'id' )
                ->on( 'companies' )
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
        Schema::dropIfExists( 'company_options' );
    }
}
