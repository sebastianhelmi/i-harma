<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DeliveryPlan;
use Illuminate\Support\Facades\Log;

class DuplicateDeliveryPlanDateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $newDeliveryPlan;
    protected $existingDeliveryPlan;

    /**
     * Create a new notification instance.
     */
    public function __construct(DeliveryPlan $newDeliveryPlan, DeliveryPlan $existingDeliveryPlan)
    {
        $this->newDeliveryPlan = $newDeliveryPlan;
        $this->existingDeliveryPlan = $existingDeliveryPlan;
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
            'new_plan_id' => $this->newDeliveryPlan->id,
            'new_plan_number' => $this->newDeliveryPlan->plan_number,
            'existing_plan_id' => $this->existingDeliveryPlan->id,
            'existing_plan_number' => $this->existingDeliveryPlan->plan_number,
            'planned_date' => $this->newDeliveryPlan->planned_date->format('d M Y'),
            'message' => 'Peringatan: Rencana pengiriman baru (' . $this->newDeliveryPlan->plan_number . ') memiliki tanggal yang sama (' . $this->newDeliveryPlan->planned_date->format('d M Y') . ') dengan rencana pengiriman yang sudah ada (' . $this->existingDeliveryPlan->plan_number . ').',
            'new_plan_id' => $this->newDeliveryPlan->id,
        ];
    }
}
