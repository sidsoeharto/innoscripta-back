<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'source_name',
        'author',
        'title',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'content',
    ];

    public function setUrlToImageAttribute($value) {
        $this->attributes['url_to_image'] = substr($value, 0, 2047);
    }

    public function user() {
        return $this->belongsToMany('App\Models\User', 'news_collection');
    }
}
