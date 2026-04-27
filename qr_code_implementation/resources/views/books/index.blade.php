<!DOCTYPE html>
<html>
<head>
    <title>All Books</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        a {
            text-decoration: none;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .add-btn {
            display: inline-block;
            margin-bottom: 15px;
            background: #4CAF50;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .book-list {
            list-style: none;
            padding: 0;
        }

        .book-item {
            background: #fff;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions a {
            margin-right: 10px;
            color: #3498db;
        }

        .actions button {
            background: #e74c3c;
            border: none;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions button:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">

<h1>All Books</h1>

<a href="{{ route('books.create') }}" class="add-btn">Add New Book</a>

<ul class="book-list">

@foreach ($books as $book)

<li class="book-item">

<div>
{{ $book->title }} by {{ $book->author }} ({{ $book->published_date }})
</div>

<div class="actions">
<a href="{{ route('books.edit', $book->id) }}">Edit</a>

<form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button type="submit">Delete</button>
</form>
</div>

</li>

@endforeach

</ul>

</div>

</body>
</html>