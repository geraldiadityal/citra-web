<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionChats extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'expire_at'
    ];

    public function chats()
    {
        return $this->hasManyThrough(Chats::class, Message::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id', 'session_chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user1_id', 'id');
    }
    public function partner()
    {
        return $this->belongsTo(User::class, 'user2_id', 'id');
    }
    public function getExpireAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
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
