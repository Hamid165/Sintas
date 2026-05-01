<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KunjunganUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kunjungan;
    public $tipe_aksi;

    public function __construct($kunjungan, $tipe_aksi = 'create')
    {
        $this->kunjungan = $kunjungan;
        $this->tipe_aksi = $tipe_aksi;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('kunjungan-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'kunjungan' => $this->kunjungan,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
