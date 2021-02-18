<?php

namespace App\Providers;

use App\Events\{AdminRepliedTicket, NewUserRegistered, UserNewTicket};
use App\Listeners\{NotifyCustomerTicketReplied, NotifyManagementTicket, SendManagementNotification};
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewUserRegistered::class => [
            SendManagementNotification::class,
        ],
        UserNewTicket::class => [
            NotifyManagementTicket::class
        ],
        AdminRepliedTicket::class => [
            NotifyCustomerTicketReplied::class
        ]
    ];
}
