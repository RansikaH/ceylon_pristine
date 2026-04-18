<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderPlacedNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        $orderTotal = number_format($order->total, 2);
        
        $title = 'New Order #' . $order->id;
        $message = "A new order has been placed for LKR {$orderTotal}.";
        $url = route('admin.orders.show', $order->id);
        
        // Notify all admins
        $admins = Admin::all();
        
        Notification::send($admins, new AdminNotification(
            $title,
            $message,
            $url,
            'order'
        ));
    }
}
