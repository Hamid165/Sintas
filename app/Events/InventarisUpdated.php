<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventarisUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventaris;
    public $tipe_aksi;

    public function __construct($inventaris, $tipe_aksi = 'create')
    {
        $this->inventaris = $inventaris;
        $this->tipe_aksi = $tipe_aksi;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('inventaris-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'inventaris' => $this->inventaris,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
