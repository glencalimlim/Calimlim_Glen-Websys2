<!DOCTYPE html>
<html>
<head>
    <title>Student QR System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f7fa;
        }

        .navbar{
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }

        .card{
            border:none;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,0.08);
        }

        .btn{
            border-radius:10px;
        }

        table{
            background:white;
            border-radius:15px;
            overflow:hidden;
        }

        input{
            border-radius:10px !important;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
<div class="container">
<a class="navbar-brand fw-bold" href="/students">🎓 Student QR System</a>
</div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>