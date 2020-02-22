<?php

namespace App\Events;

use App\message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels , InteractsWithQueue;

    public $message ;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $message )
    {
        $this->message = $message;
    }

    public function broadcastAs()
    {
        return 'my-event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('room');    
    }

    // public function broadcastWith()
    // {
    //     $this->message->load('user');
    //     return ["message" => $this->message];
    // }
}
