<?php

namespace App\Notifications;

use App\Models\Spb;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSpbApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $spb;

    public function __construct(Spb $spb)
    {
        $this->spb = $spb;
    }

    public function via($notifiable)
    {
        return ['database']; // Tambahkan 'mail' jika ingin email
    }

    public function toArray($notifiable)
    {
        return [
            'spb_id' => $this->spb->id,
            'spb_number' => $this->spb->spb_number,
            'project_id' => $this->spb->project_id,
            'project_name' => $this->spb->project->name,
            'created_by' => $this->spb->requester->name,
            'created_at' => $this->spb->created_at,
            'message' => 'SPB baru membutuhkan persetujuan Project Manager',
        ];
    }

    // public function toMail($notifiable)
    // {
    //     $url = route('pm.spb-approvals.show', $this->spb);
    //     return (new MailMessage)
    //         ->subject('SPB Baru Menunggu Persetujuan')
    //         ->greeting('Halo ' . $notifiable->name)
    //         ->line('Terdapat SPB baru yang membutuhkan persetujuan Anda.')
    //         ->action('Lihat SPB', $url)
    //         ->line('Mohon segera ditinjau.');
    // }
}
