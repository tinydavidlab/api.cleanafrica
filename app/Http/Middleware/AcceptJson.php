<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle( Request $request, Closure $next )
    {
        if ( !$request->headers->has( 'Accept' ) ) {
            $request->headers->set( 'Accept', 'application/json' );
        }

        if ( !$request->headers->has( 'Content-Type' ) ) {
            $request->headers->set( 'Content-Type', 'application/json' );
        }

        return $next( $request );
    }
}
