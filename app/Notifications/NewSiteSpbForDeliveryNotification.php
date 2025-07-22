<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DeliveryPlan;

class NewSiteSpbForDeliveryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $deliveryPlan;

    /**
     * Create a new notification instance.
     */
    public function __construct(DeliveryPlan $deliveryPlan)
    {
        $this->deliveryPlan = $deliveryPlan;
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
            'delivery_plan_id' => $this->deliveryPlan->id,
            'plan_number' => $this->deliveryPlan->plan_number,
            'message' => 'Rencana pengiriman baru telah dibuat untuk SPB Site: ' . $this->deliveryPlan->plan_number,
            'delivery_plan_id' => $this->deliveryPlan->id,
        ];
    }
}
