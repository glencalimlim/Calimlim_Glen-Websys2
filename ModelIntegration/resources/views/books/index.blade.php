<h1>All Books</h1>

<a href="{{ route('books.create') }}">Add New Book</a>

<ul>

@foreach ($books as $book)

<li>

{{ $book->title }} by {{ $book->author }} ({{ $book->published_date }})

<a href="{{ route('books.edit', $book->id) }}">Edit</a>

<form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">

@csrf

@method('DELETE')

<button type="submit">Delete</button>

</form>

</li>

@endforeach

</ul>

