<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public bool $afterCommit = true;

    public function __construct(
        public int $jadwalId,
        public int $nomorDilayani
    ) {
    }

    public function broadcastOn(): array
    {
        return [new Channel('queues')];
    }

    public function broadcastAs(): string
    {
        return 'queue.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'jadwal_id' => $this->jadwalId,
            'nomor_dilayani' => $this->nomorDilayani,
        ];
    }
}
