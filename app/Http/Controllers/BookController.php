<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function show(Request $request)
    {
        $isbn = $request->query('isbn');

        $data = ['isbn' => $isbn];

        if (!$isbn) {
            return view('book', $data);
        }

        $validator = Validator::make(['isbn' => $isbn], [
            'isbn' => 'required|regex:/^\d{10,13}$/'
        ]);

        if ($validator->fails()) {
            $data['error'] = 'Invalid ISBN format. Please enter a 10 or 13-digit ISBN.';
            return view('findbook', $data);
        }

        $bookData = Cache::remember("book_{$isbn}", 3600, function () use ($isbn) { 
            try {
                $response = Http::timeout(10)->get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}");

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