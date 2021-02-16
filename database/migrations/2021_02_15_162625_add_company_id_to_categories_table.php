<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'categories', function ( Blueprint $table ) {
            $table->unsignedBigInteger( 'company_id' );

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
        Schema::table( 'categories', function ( Blueprint $table ) {
            $table->dropColumn( 'company_id' );
        } );
    }
}
