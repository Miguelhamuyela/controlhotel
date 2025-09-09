<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'chatGroupId',
        'senderId',
        'senderType',
        'senderEmail',  
        'message',
    ];

    public function sender()
    {
        if ($this->senderType === 'admin') {
            return $this->belongsTo(\App\Models\Admin::class, 'senderId');
        }
        return $this->belongsTo(\App\Models\Employeee::class, 'senderId');
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\ChatGroup::class, 'chatGroupId');
    }
}