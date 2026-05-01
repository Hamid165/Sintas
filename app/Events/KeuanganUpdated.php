<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KeuanganUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaksi;
    public $tipe_aksi; // 'create', 'update', 'delete'

    /**
     * Create a new event instance.
     */
    public function __construct($transaksi, $tipe_aksi = 'create')
    {
        $this->transaksi = $transaksi;
        $this->tipe_aksi = $tipe_aksi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Menggunakan public channel agar tidak perlu setup otentikasi channel yang rumit untuk demo ini
        return [
            new Channel('keuangan-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'transaksi' => $this->transaksi,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
