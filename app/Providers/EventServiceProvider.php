<?php

namespace App\Providers;

use App\Events\{AdminRepliedTicket, CustomerRepliedTicket, NewUserRegistered, UserNewTicket};
use App\Listeners\{NotifyAdminCustomerRepliedTicket,
    NotifyCustomerTicketReplied,
    NotifyManagementTicket,
    SendManagementNotification
};
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
        ],
        CustomerRepliedTicket::class => [
            NotifyAdminCustomerRepliedTicket::class
        ]
    ];
}
