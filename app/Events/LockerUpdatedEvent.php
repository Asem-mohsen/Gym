<?php

namespace App\Events;

use App\Models\Locker;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LockerUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $locker;

    public function __construct(Locker $locker)
    {
        $this->locker = $locker;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('lockers'),
        ];
    }
}
