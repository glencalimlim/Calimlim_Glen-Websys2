<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }

        .form-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            margin-top: 15px;
            padding: 10px;
            background: #3498db;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="form-card">

<h1>Edit Book</h1>

<form action="{{ route('books.update', $book->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Title:</label>
    <input type="text" name="title" value="{{ $book->title }}" required>

    <label>Author:</label>
    <input type="text" name="author" value="{{ $book->author }}" required>

    <label>Published Date:</label>
    <input type="date" name="published_date" value="{{ $book->published_date }}" required>

    <button type="submit">Update</button>
</form>

</div>

</body>
</html>