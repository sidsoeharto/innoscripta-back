<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class NewYorkTimesController extends Controller
{
    public function fetchNews(Request $request)
    {
        $apiKey = config('api_keys.new_york_times');
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key={$apiKey}&sort=newest";

        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');

        if (!empty($keyword)) {
            $url .= "&q=" . urlencode($keyword);
        }

        if (!empty($from)) {
            $url .= "&begin_date=" . urlencode($from);
        }

        if (!empty($to)) {
            $url .= "&end_date=" . urlencode($to);
        }

        if (!empty($category)) {
            $url .= "&fq=" . urlencode($category);
        }

        $response = Http::get($url);
        $data = json_decode($response->getBody(), true);
        $result = [];

        foreach ($data['response']['docs'] as $article) {
            $result[] = [
                'source_id' => null,
                'source_name' => $article['source'] ?? 'The New York Times',
                'author' => $article['byline']['original'],
                'title' => $article['headline']['main'],
                'description' => $article['snippet'],
                'url' => $article['web_url'],
                'url_to_image' => $article['multimedia'] ? 'https://static01.nyt.com/' . $article['multimedia'][0]['url'] : null,
                'published_at' => $article['pub_date'],
                'content' => $article['lead_paragraph'],
            ];
        }

        return response()->json($result);
    }
}
