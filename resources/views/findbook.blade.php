<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISBN Book Lookup</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <h1>ISBN Based Book Lookup</h1>

    <form method="GET" action="{{ route('book.show') }}">
        <input type="text" name="isbn" placeholder="Enter ISBN (e.g., 9780141439518)" value="{{ $isbn ?? '' }}" required>
        <button type="submit">Fetch Book Details</button>
    </form>

    @if(isset($error))
        <div class="error">
            {{ $error }}
        </div>
    @endif

    @if(isset($title))
        <div class="book-details">
            <h2><strong> Title : </strong>{{ $title }}</h2>
            @if($coverUrl)
                <img src="{{ $coverUrl }}" alt="Book Cover">
            @else
                <p>No cover image available.</p>
            @endif
            <p><strong>Authors:</strong> {{ $authors }}</p>
            <p><strong>Publisher:</strong> {{ $publisher }}</p>
            <p><strong>Pages:</strong> {{ $pages }}</p>
            <h3><strong>Description:</strong></h3>
            <p>{{ $description }}</p>

            <div class="citation">
                <p><strong>Data Source:</strong> This book information is provided by the Google Books API.</p>
                <p>For more details, visit: <a href="https://developers.google.com/books" target="_blank">Google Books API</a></p>
            </div>
        </div>
    @endif
</body>
</html>