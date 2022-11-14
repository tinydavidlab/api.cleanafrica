<?php

namespace App\Providers;

use App\Events\{AdminRepliedTicket,
    CollectorCanceledTripEvent,
    CollectorUpdatedTripEvent,
    CustomerRepliedTicket,
    NewUserRegistered,
    SendAnnouncementToCollector,
    UserNewTicket};
use App\Listeners\{NotifyAdminCollectorCanceledTrip,
    NotifyAdminCollectorUpdatedTrip,
    NotifyAdminCustomerRepliedTicket,
    NotifyCollectorOfAnnouncement,
    NotifyCustomerTicketReplied,
    NotifyManagementTicket,
    SendManagementNotification};
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
        ],
        CollectorUpdatedTripEvent::class => [
            NotifyAdminCollectorUpdatedTrip::class
        ],
        CollectorCanceledTripEvent::class => [
            NotifyAdminCollectorCanceledTrip::class
        ],
        SendAnnouncementToCollector::class => [
            NotifyCollectorOfAnnouncement::class
        ]
    ];
}
