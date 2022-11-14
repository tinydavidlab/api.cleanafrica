<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'companies', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
            $table->string( 'logo' )->nullable();
            $table->string( 'tagline' )->nullable();
            $table->string( 'email' )->nullable();
            $table->string( 'phone_number' )->nullable();
            $table->string( 'website' )->nullable();
            $table->timestamp( 'activated_at' )->nullable();
            $table->bigInteger( 'activated_by' )->nullable();
            $table->timestamps();
            $table->softDeletes();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'companies' );
    }
}
