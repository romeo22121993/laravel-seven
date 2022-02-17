<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    public function room(){
        return $this->hasOne('App\Models\Chat\ChatRoom', 'id', 'chat_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

}
