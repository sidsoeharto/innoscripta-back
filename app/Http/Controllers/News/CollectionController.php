<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\News;

class CollectionController extends Controller
{
    public function getAll (Request $request) {
        $user = auth('sanctum')->user();
 
        return $user->news;
    }

    public function store (Request $request) {
        $user = auth('sanctum')->user();
        $news = News::where('title', $request->title)->first();

        if(!$news) {
            $news = News::create([
                'source_id' => $request->source_id,
                'source_name' => $request->source_name,
                'author' => $request->author,
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
                'url_to_image' => $request->url_to_image,
                'published_at' => $request->published_at,
                'content' => $request->content,
            ]);
        }
        $user->news()->syncWithoutDetaching([$news->id]);
        
        return $user->news;
    }

    public function delete ($id) {
        $user = auth('sanctum')->user();

        $user->news()->detach($id);
 
        return $user->news;
    }
}
