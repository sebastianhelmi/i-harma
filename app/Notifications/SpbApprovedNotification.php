<?php

namespace App\Notifications;

use App\Models\Spb;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SpbApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $spb;

    public function __construct(Spb $spb)
    {
        $this->spb = $spb;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'spb_id' => $this->spb->id,
            'spb_number' => $this->spb->spb_number,
            'project_id' => $this->spb->project_id,
            'project_name' => $this->spb->project->name,
            'approved_by' => $this->spb->approver ? $this->spb->approver->name : null,
            'approved_at' => $this->spb->approved_at,
            'message' => 'SPB Anda telah disetujui oleh Project Manager',
        ];
    }
}
