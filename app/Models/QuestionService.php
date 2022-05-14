<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionService extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'services_id',
        'question',
        'answer',
    ];
}
