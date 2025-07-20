<?php

namespace App\Notifications;

use App\Models\Po;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPoCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $po;

    public function __construct(Po $po)
    {
        $this->po = $po;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'po_id' => $this->po->id,
            'po_number' => $this->po->po_number,
            'spb_id' => $this->po->spb_id,
            'project_id' => $this->po->spb->project_id,
            'project_name' => $this->po->spb->project->name,
            'created_by' => $this->po->creator->name ?? null,
            'order_date' => $this->po->order_date,
            'message' => 'PO baru telah dibuat dan verifikasi penerimaan terima barang',
        ];
    }
}
