<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    use HasFactory;

    protected $fillable = [
        'content',
        'session_chats_id',
    ];

    public function chats()
    {
        return $this->hasMany(Chats::class);
    }

    public function createForSender($session_chat_id, $user_id)
    {
        return $this->chats()->create([
            'session_chat_id' => $session_chat_id,
            'type' => 0,
            'user_id' => $user_id,
        ]);
    }

    public function createForReceiver($session_chat_id, $to_user)
    {
        return $this->chats()->create([
            'session_chat_id' => $session_chat_id,
            'type' => 1,
            'user_id' => $to_user,
        ]);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
