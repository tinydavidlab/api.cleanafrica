<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * The database schema.
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schema = Schema::connection( $this->getConnection() );
    }

    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return config( 'passport.storage.database.connection' );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement( 'SET SESSION sql_require_primary_key=0' );
        $this->schema->create( 'oauth_clients', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'user_id' )->nullable()->index();
            $table->string( 'name' );
            $table->string( 'secret', 100 )->nullable();
            $table->string( 'provider' )->nullable();
            $table->text( 'redirect' );
            $table->boolean( 'personal_access_client' );
            $table->boolean( 'password_client' );
            $table->boolean( 'revoked' );
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
        $this->schema->dropIfExists( 'oauth_clients' );
    }
};
