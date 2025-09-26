<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookSearchRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;


class BookController extends Controller
{
    public function show(BookSearchRequest $request)
    {
        $validated = $request->validated();
        $isbn = $request->query('isbn');

        $data = ['isbn' => $isbn];

        $bookData = Cache::remember("book_{$isbn}", 3600, function () use ($isbn) { 
            try {
                $baseUrl = config('api.google_books.base_url');
                $queryParam = config('api.google_books.query_param');
                $timeout = config('api.google_books.timeout');

                $response = Http::timeout($timeout)->get("{$baseUrl}?{$queryParam}{$isbn}");

                if ($response->failed()) {
                    return ['error' => 'Unable to fetch details google api. Please try again later.'];
                }

                $apiData = $response->json();
                $volumeInfo = $apiData['items'][0]['volumeInfo'] ?? null;

                if (!$volumeInfo) {
                    return ['error' => 'No book found for this ISBN. Please check ISBN and try again.'];
                }

                return [
                    'title' => $volumeInfo['title'] ?? 'N/A',
                    'authors' => implode(', ', $volumeInfo['authors'] ?? []),
                    'description' => $volumeInfo['description'] ?? 'No summary available.',
                    'coverUrl' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
                    'pages' => $volumeInfo['pageCount'] ?? 'N/A',
                    'publisher' => $volumeInfo['publisher'] ?? 'N/A',
                ];
            } catch (\Exception $e) {
                return ['error' => 'Network error while fetching book details. Please try again.'];
            }
        });

        if (isset($bookData['error'])) {
            $data['error'] = $bookData['error'];
        } else {
            $data = array_merge($data, $bookData);
        }

        return view('findbook', $data);
    }
}