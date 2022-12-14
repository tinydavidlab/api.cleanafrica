<?php

namespace Deployer;

require 'lumen-recipe.php';

// Config

set( 'repository', 'git@github.com:tinydavidlab/api.cleanafrica.git' );

add( 'shared_files', [] );
add( 'shared_dirs', [] );
add( 'writable_dirs', [] );

// Hosts

host( 'cleanafrica.co.uk' )
    ->set( 'remote_user', 'root' )
    ->set( 'deploy_path', '/var/www/api.cleanafrica' );

// Hooks

after( 'deploy:failed', 'deploy:unlock' );
