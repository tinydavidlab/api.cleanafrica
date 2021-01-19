<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get( '/', function () { } );

/* ============= Authentication ============= */
$router->group( [ 'prefix' => 'auth' ], function ( $router ) {
    $router->post( 'login', 'AuthController@login' );
    $router->post( 'register', 'AuthController@register' );
} );

$router->group( [ 'prefix' => 'auth' ], function () use ( $router ) {
    $router->get( 'me', 'AuthController@me' );
} );

$router->group( [ 'prefix' => 'v1', 'namespace' => 'Api' ], function ( $router ) {
    /* ============= Authentication ============= */
    $router->post( 'login', 'Auth\LoginController@store' );
    $router->post( 'register', 'Auth\LoginController@new' );

    /* ============= Core ============= */
    $router->post( 'login', 'Auth\LoginController@store' );
    $router->post( 'register', 'Auth\LoginController@new' );

    /* ============= Trips ============= */
    $router->get( 'trips', 'TripController@index' );
    $router->get( 'trips/{id}', 'TripController@show' );
    $router->post( 'trips', 'TripController@store' );
    $router->put( 'trips/{id}', 'TripController@update' );
    $router->delete( 'trips/{id}', 'TripController@destroy' );

    /* ============= Completed Trips ============= */
    $router->post( 'trips/{id}/completed', 'CompletedTripController@store' );

    /* ============= Customers ============= */
    $router->get( 'customers', 'CustomerController@index' );
    $router->get( 'customers/{id}', 'CustomerController@show' );
    $router->post( 'customers', 'CustomerController@store' );
    $router->put( 'customers/{id}', 'CustomerController@update' );
    $router->delete( 'customers/{id}', 'CustomerController@destroy' );

    /* ============= Companies============= */
    $router->get( 'companies', 'CompanyController@index' );
    $router->post( 'companies', 'CompanyController@store' );
    $router->get( 'companies/{id}', 'CompanyController@show' );
    $router->put( 'companies/{id}', 'CompanyController@update' );
    $router->delete( 'companies/{id}', 'CompanyController@destroy' );

    /* ============= Company Agents ============= */
    $router->get( 'companies/{id}/agents', 'CompanyAgentController@index' );
    $router->get( 'companies/{id}/agents/{agent_id}', 'CompanyAgentController@show' );
    $router->post( 'companies/{id}/agents', 'CompanyAgentController@store' );
    $router->delete( 'companies/{id}/agents/{agent_id}', 'CompanyAgentController@destroy' );

    /* ============= Company Trips ============= */
    $router->get( 'companies/{id}/trips', 'CompanyTripController@index' );
    $router->post( 'companies/{id}/trips', 'CompanyTripController@store' );

    /* ============= Agents ============= */
    $router->get( 'agents', 'AgentController@index' );
    $router->get( 'agents/{id}', 'AgentController@show' );
    $router->post( 'agents', 'AgentController@store' );
    $router->put( 'agents/{id}', 'AgentController@update' );
    $router->delete( 'agents/{id}', 'AgentController@destroy' );

    /* ============= Trucks ============= */
    $router->get( 'trucks', 'TruckController@index' );
    $router->get( 'trucks/{id}', 'TruckController@show' );
    $router->post( 'trucks', 'TruckController@store' );
    $router->put( 'trucks/{id}', 'TruckController@update' );
    $router->delete( 'trucks/{id}', 'TruckController@destroy' );

    /* ============= Feedback ============= */
    $router->get( 'feedback', 'FeedbackController@index' );
    $router->get( 'feedback/{id}', 'FeedbackController@show' );
    $router->post( 'feedback', 'FeedbackController@store' );
    $router->put( 'feedback/{id}', 'FeedbackController@update' );
    $router->delete( 'feedback/{id}', 'FeedbackController@destroy' );

    /* ============= Admins ============= */
    $router->get( 'admins', 'AdminController@index' );
    $router->get( 'admins/{id}', 'AdminController@show' );
    $router->post( 'admins', 'AdminController@store' );
    $router->put( 'admins/{id}', 'AdminController@update' );
    $router->delete( 'admins/{id}', 'AdminController@destroy' );

} );
