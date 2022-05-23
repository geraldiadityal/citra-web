<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomChat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partners_id',
        'users_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function partner()
    {
        return $this->belongsTo(CitraPartner::class, 'partners_id', 'id');
    }
}
