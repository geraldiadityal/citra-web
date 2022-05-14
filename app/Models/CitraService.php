<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitraService extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'services';
    protected $fillable = [
        'description',
    ];

    public function partners()
    {
        return $this->hasMany(CitraPartner::class, 'services_id', 'id');
    }
    public function questions()
    {
        return $this->hasMany(QuestionService::class, 'services_id', 'id');
    }
    public function clients()
    {
        return $this->hasMany(CitraClient::class, 'services_id', 'id');
    }
}
