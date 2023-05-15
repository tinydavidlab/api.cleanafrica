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
        if ( app()->environment() == 'production' )
            DB::statement( 'SET SESSION sql_require_primary_key=0' );
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string( 'type' );
            $table->morphs( 'announceable' );
            $table->string( 'title' );
            $table->text( 'content' );
            $table->string( 'photo' )->nullable();
            $table->enum( 'priority', TicketPriority::getValues() )->default( TicketPriority::LOW() );
            $table->timestamps();

        });
        if ( app()->environment() == 'production' )
            DB::statement( 'SET SESSION sql_require_primary_key=1' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
