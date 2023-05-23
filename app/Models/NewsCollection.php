<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'user_id'
    ];
}
