<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditKeuanganUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $audit;
    public $tipe_aksi;

    public function __construct($audit, $tipe_aksi = 'create')
    {
        $this->audit = $audit;
        $this->tipe_aksi = $tipe_aksi;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('audit-keuangan-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'audit' => $this->audit,
            'tipe_aksi' => $this->tipe_aksi,
        ];
    }
}
