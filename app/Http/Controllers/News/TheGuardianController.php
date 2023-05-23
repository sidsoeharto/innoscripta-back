<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TheGuardianController extends Controller
{
    public function fetchNews(Request $request)
    {
        $apiKey = config('api_keys.guardian');
        $url = "https://content.guardianapis.com/search?api-key={$apiKey}";

        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');

        if (!empty($keyword)) {
            $url .= "&q=" . urlencode($keyword);
        }

        if (!empty($from)) {
            $url .= "&from-date=" . urlencode($from);
        }

        if (!empty($to)) {
            $url .= "&to-date=" . urlencode($to);
        }

        if (!empty($category)) {
            $url .= "&section=" . urlencode($category);
        }

        $response = Http::get($url);
        $data = json_decode($response->getBody(), true);
        $result = [];

        foreach ($data['response']['results'] as $article) {
            $result[] = [
                'source_id' => $article['sectionId'],
                'source_name' => $article['sectionName'],
                'author' => $article['pillarName'] ?? null,
                'title' => $article['webTitle'],
                'description' => null,
                'url' => $article['webUrl'],
                'url_to_image' => null,
                'published_at' => $article['webPublicationDate'],
                'content' => null,
            ];
        }

        return response()->json($result);
    }
}
