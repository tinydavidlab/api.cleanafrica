<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get( '/', function () {
    return "Clean Kenya API Project";
} );

/* ============= Authentication ============= */
$router->group( [ 'prefix' => 'auth' ], function () use ( $router ) {
    $router->post( 'login', 'AuthController@login' );
    $router->post( 'register', 'AuthController@register' );
} );

$router->group( [ 'prefix' => 'v1', 'namespace' => 'Api' ], function () use ( $router ) {
    $router->post( 'auth/token', 'UserTokenController@store' );
} );

$router->group( [ 'prefix' => 'auth' ], function () use ( $router ) {
    $router->get( 'me', 'AuthController@me' );
} );

$router->group( [ 'prefix' => 'v1', 'namespace' => 'Api' ], function () use ( $router ) {
    /* ============= Authentication ============= */
    $router->post( 'login', 'Auth\LoginController@store' );
    $router->post( 'register', 'Auth\LoginController@new' );

    /* ============= Core ============= */
    $router->post( 'login', 'Auth\LoginController@store' );
    $router->post( 'register', 'Auth\LoginController@new' );

    /* ============= Trips ============= */
    $router->post( 'trips', 'TripController@store' );
    $router->get( 'trips', 'TripController@index' );
    $router->get( 'trips/{id}', 'TripController@show' );
    $router->put( 'trips/{id}', 'TripController@update' );
    $router->delete( 'trips/{id}', 'TripController@destroy' );
    $router->get( 'trips/{date}/per_date', 'TripController@getTripsPerDate' );
    $router->get( 'trips_per_week', 'TripController@getTripsForThisWeek' );
    $router->post( 'assign_trips', 'TripController@assignMultipleTrucksToTrips' );
    $router->post( 'optimise_trips', 'TripController@optimiseTrips' );

    /* ============= Completed Trips ============= */
    $router->post( 'trips/{id}/completed', 'CompletedTripController@store' );
    $router->get( 'trips/{id}/complete_without_image', 'CompletedTripController@completeWithoutImages' );

    /* ============= Canceled Trips ============= */
    $router->get( 'trips/{id}/canceled', 'CompletedTripController@cancelTrip' );

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
    $router->get( 'companies/{id}/type/{type}', 'CompanyAgentController@getCompanyAgentByType' );
    // get trips for collector
    $router->get( 'collectors/{id}/{date}/{status}', 'CompanyAgentController@getTripsForSpecificTruckAndAgent' );

    /* ============= Company Customer ============= */
    $router->get( 'companies/{id}/customers', 'CompanyCustomerController@index' );
    $router->get( 'companies/{id}/customers/{customer_id}', 'CompanyCustomerController@show' );
    $router->post( 'companies/{id}/customers', 'CompanyCustomerController@store' );
    $router->delete( 'companies/{id}/customers/{customer_id}', 'CompanyCustomerController@destroy' );

    /* ============= Company Trips ============= */
    $router->get( 'companies/{id}/trips', 'CompanyTripController@index' );
    $router->post( 'companies/{id}/trips', 'CompanyTripController@store' );
    $router->get( 'companies/{id}/trips/{status}/{date}', 'CompanyTripController@getCompanyTripsPerStatusAndDate' );
    $router->get( 'companies/{id}/trips/{status}', 'CompanyTripController@getCompanyTripsPerStatus' );
    $router->get( 'companies/{id}/trip_date/{date}', 'CompanyTripController@getTripsPerDateForCompany' );
    $router->get( 'companies/{id}/delete_trips', 'CompanyTripController@deleteForCompany' );
    $router->get( 'truncate_trips', 'CompanyTripController@truncateTrips' );
    $router->get( 'companies/{id}/weekly_trips', 'CompanyTripController@getCompanyTripsForTheWeek' );

    /* ============= Company Trips ============= */
    $router->get( 'companies/{id}/categories', 'CompanyCategoryController@index' );

    /* ============= Agents ============= */
    $router->get( 'agents', 'AgentController@index' );
    $router->get( 'agents/{id}', 'AgentController@show' );
    $router->post( 'agents', 'AgentController@store' );
    $router->put( 'agents/{id}', 'AgentController@update' );
    $router->delete( 'agents/{id}', 'AgentController@destroy' );
    $router->get( 'agents/{type}/type', 'AgentController@getAgentByType' );

    /* ============= Trucks ============= */
    $router->get( 'trucks', 'TruckController@index' );
    $router->get( 'trucks/{id}', 'TruckController@show' );
    $router->post( 'trucks', 'TruckController@store' );
    $router->put( 'trucks/{id}', 'TruckController@update' );
    $router->delete( 'trucks/{id}', 'TruckController@destroy' );
    //$router->post( 'assign_trucks/{id}/agents', 'TruckController@assignTruckToCollector' );

    /* ============= Company Trucks ============= */
    $router->get( 'companies/{id}/trucks', 'TruckController@trucksForCompany' );

    /* ============= Feedback ============= */
    $router->get( 'feedback', 'FeedbackController@index' );
    $router->get( 'feedback/{id}', 'FeedbackController@show' );
    $router->post( 'feedback', 'FeedbackController@store' );
    $router->put( 'feedback/{id}', 'FeedbackController@update' );
    $router->delete( 'feedback/{id}', 'FeedbackController@destroy' );
    $router->get( 'companies/{id}/feedback', 'FeedbackController@getFeedBackForCompany' );

    /* ============= Admins ============= */
    $router->get( 'admins', 'AdminController@index' );
    $router->get( 'admins/{id}', 'AdminController@show' );
    $router->post( 'admins', 'AdminController@store' );
    $router->put( 'admins/{id}', 'AdminController@update' );
    $router->delete( 'admins/{id}', 'AdminController@destroy' );
    $router->get( 'companies/{id}/admins', 'AdminController@getAdminForCompany' );

    /* ============= Property Registration ============= */
    $router->post( 'property/registration', 'PropertyRegistrationController@store' );
    $router->get( 'property/registration/check', 'PropertyRegistrationController@check' );

    /* ============= Statistics ============= */
    $router->get( 'companies/{id}/stats', 'DashBoardController@index' );
    $router->get( 'statistics', 'DashBoardController@getStatistics' );
    $router->get( 'statistics_per_date/{criteria}', 'DashBoardController@getStatisticsPerCriteria' );
    $router->get( 'companies/{id}/statistics_per_date/{criteria}', 'DashBoardController@getStatisticPerCriteriaPerCompany' );

    /* ============= Categories ============= */
    $router->get( 'categories', 'CategoryController@index' );
    $router->post( 'categories', 'CategoryController@store' );
    $router->get( 'categories/{id}', 'CategoryController@show' );
    $router->put( 'categories/{id}', 'CategoryController@update' );
    $router->delete( 'categories/{id}', 'CategoryController@destroy' );

    /* ============= Company Tickets ============= */
    $router->get( 'companies/{id}/tickets', 'CompanyTicketController@getTicketsForCompany' );
    $router->get( 'companies/{id}/ticket_date/{date}', 'CompanyTicketController@getCompanyTicketsPerDate' );

    /* ============= Tickets ============= */
    $router->get( 'tickets', 'TicketController@index' );
    $router->get( 'tickets/{id}', 'TicketController@show' );
    $router->post( 'tickets', 'TicketController@store' );
    $router->get( 'tickets/{id}/close', 'TicketController@closeTicket' );

    /* ============= Ticket Replies ============= */
    $router->get( 'tickets/{id}/replies', 'TicketReplyController@index' );

    /* ============= CSV Uploader ============= */
    $router->post( 'uploadcsv/{type}', 'CSVImporter@import' );

    /* ============= Announcements ============= */
    $router->get( 'companies/{id}/announcements', 'AnnouncementController@index' );
    $router->post( 'announcements', 'AnnouncementController@store' );
    $router->get( 'announcements/{id}', 'AnnouncementController@show' );
    $router->put( 'announcements/{id}/', 'AnnouncementController@update' );
    $router->delete( 'announcements/{id}', 'AnnouncementController@destroy' );
} );

$router->group( [ 'middleware' => 'auth:customer', 'prefix' => 'v1', 'namespace' => 'Api' ], function () use ( $router ) {
    $router->get( 'user/tickets', 'UserTicketController@index' );
    $router->post( 'user/tickets', 'UserTicketController@store' );
    $router->post( 'user/tickets/{id}/replies', 'UserTicketController@reply' );
} );

$router->group( [ 'middleware' => 'auth:admin', 'prefix' => 'v1', 'namespace' => 'Api' ], function () use ( $router ) {
    /* ============= Ticket Replies ============= */
    $router->post( 'tickets/{id}/replies', 'TicketReplyController@store' );
} );

$router->group( [ 'middleware' => 'auth:customer', 'prefix' => 'v1', 'namespace' => 'Api' ], function () use ( $router ) {
    $router->post( 'me/trips', 'UserTripController@store' );
    $router->get( 'me/trips', 'UserTripController@index' );
} );
