<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{

    use HasFactory;

    protected $fillable = ['message_id', 'session_chat_id', 'user_id', 'type',];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
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
