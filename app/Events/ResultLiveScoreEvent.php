<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResultLiveScoreEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;
    public $flag;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($request, $data)
    {
        //
        $this->code = $request->code;
        $this->flag = $request->flag;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('result-live-score.' . $this->code);
    }

    public function broadcastWith()
    {
        return [
            'code' => $this->code,
            'flag' => $this->flag,
            'data' => $this->data,
        ];
    }
}
