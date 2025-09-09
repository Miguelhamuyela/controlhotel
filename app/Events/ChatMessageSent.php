<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\Admin;
use App\Models\Employeee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function broadcastOn()
    {
        return new Channel('chat-group.'.$this->chatMessage->chatGroupId);
    }

    public function broadcastWith()
    {
        $sender = $this->chatMessage->sender;

        // define um "senderName" e "photoUrl" (adapte aos campos do seu BD)
        if ($sender instanceof Admin) {
            $name     = $sender->employee ? ($sender->employee->fullName ?? $sender->email) : $sender->email;
            $photoUrl = asset('images/default-avatar.png'); // troque se tiver foto
        } else {
            // se for Employeee
            $name     = $sender->fullName ?? $sender->email;
            $photoUrl = asset('images/default-avatar.png');
        }

        return [
            'id'          => $this->chatMessage->id,
            'chatGroupId' => $this->chatMessage->chatGroupId,
            'senderId'    => $this->chatMessage->senderId,
            'senderType'  => $this->chatMessage->senderType,
            'senderName'  => $name,
            'photoUrl'    => $photoUrl,
            'message'     => $this->chatMessage->message,
            'created_at'  => $this->chatMessage->created_at->toDateTimeString(),
        ];
    }
}
