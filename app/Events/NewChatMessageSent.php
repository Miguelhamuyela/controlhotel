<?php
namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $chatMessage;

    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }
    
    public function broadcastOn()
    {
        return new Channel('chat-group.' . $this->chatMessage->chatGroupId);
    }
    
    public function broadcastWith()
    {
        // Usa o campo senderEmail armazenado na mensagem para exibição.
        $senderName = !empty($this->chatMessage->senderEmail)
            ? $this->chatMessage->senderEmail
            : 'Usuário';
        
        $photoUrl = $this->chatMessage->senderType === 'admin'
            ? asset('images/admin-default.png')
            : asset('images/employee-default.png');
            
        return [
            'id'           => $this->chatMessage->id,
            'chatGroupId'  => $this->chatMessage->chatGroupId,
            'senderId'     => $this->chatMessage->senderId,
            'senderType'   => $this->chatMessage->senderType,
            'senderName'   => $senderName,
            'photoUrl'     => $photoUrl,
            'message'      => $this->chatMessage->message,
            'created_at'   => $this->chatMessage->created_at->toDateTimeString(),
        ];
    }
}