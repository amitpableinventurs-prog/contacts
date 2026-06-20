<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('team.'.$this->message->team_id)];
    }

    public function broadcastWith(): array
    {
        $this->message->loadMissing('contact');
        return [
            'id' => $this->message->id,
            'channel' => $this->message->channel,
            'direction' => $this->message->direction,
            'body' => $this->message->body,
            'from_number' => $this->message->from_number,
            'sent_at' => $this->message->sent_at?->toIso8601String(),
            'contact' => $this->message->contact ? [
                'id' => $this->message->contact->id,
                'name' => $this->message->contact->name,
            ] : null,
        ];
    }
}
