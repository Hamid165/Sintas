<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratKeluarUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $surat;
    public $tipe_aksi;

    public function __construct($surat, $tipe_aksi = 'create')
    {
        $this->surat = $surat;
        $this->tipe_aksi = $tipe_aksi;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('surat-keluar-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'surat' => $this->surat,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
