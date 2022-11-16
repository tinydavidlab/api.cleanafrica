<?php

namespace Deployer;

require 'lumen-recipe.php';

// Config

set( 'repository', 'git@github.com:tinydavidlab/api.cleanafrica.git' );

add( 'shared_files', [ '.env' ] );
add( 'shared_dirs', [ 'vendor', 'storage' ] );
add( 'writable_dirs', [] );

// Hosts

host( 'api.cleanafrica.co.uk' )
    ->set( 'remote_user', 'root' )
    ->set( 'deploy_path', '/var/www/api.cleanafrica' );

// Hooks

after( 'deploy:failed', 'deploy:unlock' );
