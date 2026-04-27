<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>

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
            background: #4CAF50;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="form-card">

<h1>Add New Book</h1>

<form action="{{ route('books.store') }}" method="POST">
@csrf

<label for="title">Title:</label>
<input type="text" id="title" name="title" required>

<label for="author">Author:</label>
<input type="text" id="author" name="author" required>

<label for="published_date">Published Date:</label>
<input type="date" id="published_date" name="published_date" required>

<button type="submit">Save</button>
</form>

</div>

</body>
</html>