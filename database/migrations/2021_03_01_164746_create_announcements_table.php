<?php

use App\Enums\TicketPriority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement( 'SET SESSION sql_require_primary_key=0' );
        Schema::create( 'announcements', function ( Blueprint $table ) {
            $table->uuid( 'id' )->primary();
            $table->unsignedBigInteger( 'company_id' );
            $table->string( 'type' );
            $table->morphs( 'announceable' );
            $table->string( 'title' );
            $table->text( 'content' );
            $table->string( 'photo' )->nullable();
            $table->enum( 'priority', [ 'LOW', 'MEDIUM', 'URGENT', 'VERY_URGENT' ] )->default( TicketPriority::LOW->value );
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
        Schema::dropIfExists( 'announcements' );
    }
}
