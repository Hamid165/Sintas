<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnakUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $anak;
    public $tipe_aksi;

    public function __construct($anak, $tipe_aksi = 'create')
    {
        $this->anak = $anak;
        $this->tipe_aksi = $tipe_aksi;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('anak-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'anak' => $this->anak,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
