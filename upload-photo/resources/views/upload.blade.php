<!DOCTYPE html>
<html>
<head>
    <title>Laravel Image Upload (Single + Multiple)</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        form {
            background: #fff;
            padding: 20px;
            margin: 15px auto;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        input[type="file"] {
            margin: 10px 0;
            padding: 5px;
        }

        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #45a049;
        }

        p {
            text-align: center;
            font-weight: bold;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .photo-card {
            background: #fff;
            margin: 10px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .photo-card:hover {
            transform: scale(1.05);
        }

        img {
            border-radius: 8px;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .delete-btn {
            margin-top: 8px;
            background: #e74c3c;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination div {
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        /* Pagination Styling - Smaller Size */
.pagination nav {
    display: flex;
    justify-content: center;
}

.pagination svg {
    width: 14px;
    height: 14px;
}

.pagination span,
.pagination a {
    padding: 4px 8px;
    font-size: 12px;
    margin: 2px;
    border-radius: 5px;
}

.pagination a {
    text-decoration: none;
    background: #f0f0f0;
    color: #333;
}

.pagination a:hover {
    background: #4CAF50;
    color: white;
}

.pagination span[aria-current="page"] {
    background: #4CAF50;
    color: white;
}
    </style>
</head>
<body>

<h1>Calimlim, Glen Paul T.</h1>

<h1>Single Image Upload</h1>
<form action="{{ route('photos.store.single') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <br>
    <button type="submit">Upload</button>
</form>

<h1>Multiple Images Upload</h1>
<form action="{{ route('photos.store.multiple') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="images[]" multiple required>
    <br>
    <button type="submit">Upload</button>
</form>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<div class="gallery">
@foreach($photos as $photo)
    <div class="photo-card">
        <img src="{{ asset('images/' . $photo->image) }}"><br>

        <form action="{{ route('photos.destroy', $photo->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </div>
@endforeach
</div>

<div class="pagination">
    <div>
        {{ $photos->links() }}
    </div>
</div>

</body>
</html>