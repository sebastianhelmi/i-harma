<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WorkshopOutput;

class NewWorkshopOutputForDeliveryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $workshopOutput;

    /**
     * Create a new notification instance.
     */
    public function __construct(WorkshopOutput $workshopOutput)
    {
        $this->workshopOutput = $workshopOutput;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'workshop_output_id' => $this->workshopOutput->id,
            'message' => 'Output workshop baru memerlukan pengiriman: ' . $this->workshopOutput->inventory->item_name . ' (' . $this->workshopOutput->quantity_produced . ' ' . $this->workshopOutput->inventory->unit . ')',
            'workshop_output_id' => $this->workshopOutput->id,
        ];
    }
}
