<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsAPIController extends Controller
{
    public function fetchNews(Request $request)
    {
        $apiKey = config('api_keys.news_api');
        $url = "https://newsapi.org/v2/top-headlines?country=id&apiKey={$apiKey}";

        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');

        if (!empty($keyword)) {
            $url .= "&q=" . urlencode($keyword);
        }

        if (!empty($from)) {
            $url .= "&from=" . urlencode($from);
        }

        if (!empty($to)) {
            $url .= "&to=" . urlencode($to);
        }

        if (!empty($category)) {
            $url .= "&category=" . urlencode($category);
        }

        $response = Http::get($url);
        $data = json_decode($response->getBody(), true);
        $result = [];

        foreach ($data['articles'] as $article) {
            $parsedUrl = parse_url($article['urlToImage']);
            $queryParams = [];
            parse_str($parsedUrl['query'] ?? '', $queryParams);

            unset($queryParams['width'], $queryParams['height'], $queryParams['auto'], $queryParams['overlay-align'], $queryParams['overlay-width'], $queryParams['overlay-base64'], $queryParams['enable']);

            $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
            $host = $parsedUrl['host'] ?? '';
            $path = $parsedUrl['path'] ?? '';

            $cleanUrl = $scheme . $host . $path . '?' . http_build_query($queryParams);
            $truncatedUrlToImage = Str::limit($cleanUrl, 2047);

            $result[] = [
                'source_id' => $article['source']['id'],
                'source_name' => $article['source']['name'],
                'author' => $article['author'],
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'url_to_image' => $article['urlToImage'] ? $truncatedUrlToImage : null,
                'published_at' => $article['publishedAt'],
                'content' => $article['content'],
            ];
        }

        return response()->json($result);
    }
}
