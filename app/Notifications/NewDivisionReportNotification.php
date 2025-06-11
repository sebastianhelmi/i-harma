<?php

namespace App\Notifications;

use App\Models\DivisionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDivisionReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;

    public function __construct(DivisionReport $report)
    {
        $this->report = $report;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('project-manager.reports.show', $this->report);

        return (new MailMessage)
            ->subject('Laporan Divisi Baru: ' . $this->report->division->name)
            ->greeting('Halo ' . $notifiable->name)
            ->line('Terdapat laporan baru dari divisi ' . $this->report->division->name)
            ->line('Proyek: ' . $this->report->project->name)
            ->line('Tipe Laporan: ' . ucfirst($this->report->report_type))
            ->line('Tanggal Laporan: ' . $this->report->report_date->format('d/m/Y'))
            ->action('Lihat Laporan', $url)
            ->line('Mohon segera ditinjau dan diberikan feedback.');
    }

    public function toArray($notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'project_id' => $this->report->project_id,
            'division_id' => $this->report->division_id,
            'report_type' => $this->report->report_type,
            'report_date' => $this->report->report_date,
            'division_name' => $this->report->division->name,
            'project_name' => $this->report->project->name,
        ];
    }
}
