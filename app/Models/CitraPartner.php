<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitraPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'users_id',
        'services_id',
    ];

    public function chats()
    {
        return $this->hasMany(RoomChat::class, 'partners_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(CitraService::class, 'services_id', 'id');
    }
}
