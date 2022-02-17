<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat\ChatMessage;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';

    public function messages(){
        return $this->hasMany('ChatMessage');
    }

}
