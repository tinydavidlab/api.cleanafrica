<?php

namespace Deployer;

require 'lumen-recipe.php';

// Config

set( 'repository', 'git@github.com:tinydavidlab/api.cleanafrica.git' );

add( 'shared_files', [] );
add( 'shared_dirs', [ 'libs' ] );
add( 'writable_dirs', [] );

// Hosts

host( 'api.cleanafrica.com' )
    ->set( 'remote_user', 'root' )
    ->set( 'deploy_path', '~/var/www/api.cleanafrica' );

// Hooks

after( 'deploy:failed', 'deploy:unlock' );
