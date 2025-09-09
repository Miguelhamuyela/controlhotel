<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $fillable = [
        'name',
        'groupType',
        'departmentId',
        'headId',
        'conversation_key',
    ];

    // Um grupo tem muitas mensagens.
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chatGroupId');
    }
}
