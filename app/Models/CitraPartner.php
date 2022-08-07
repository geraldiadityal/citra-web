<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitraPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'users_id',
        'services_id',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(CitraService::class, 'services_id', 'id');
    }
    public function getActiveAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
