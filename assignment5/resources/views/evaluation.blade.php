<!DOCTYPE html>
<html>
<head>
    <title>Student Evaluation</title>
</head>
<body>

<h2>Student Evaluation System (GET Method)</h2>

<form method="GET" action="/evaluation">

    Name: <input type="text" name="name"><br><br>
    Prelim: <input type="number" name="prelim"><br><br>
    Midterm: <input type="number" name="midterm"><br><br>
    Final: <input type="number" name="final"><br><br>

    <button type="submit">Evaluate</button>
</form>

<hr>

@if($submit)

    @php
        $avg = ($prelim + $midterm + $final) / 3;
    @endphp

    <p>Name: {{ $name }}</p>
    <p>Average: {{ number_format($avg, 2) }}</p>

    <p>Letter Grade:
        @if($avg >= 90) A
        @elseif($avg >= 80) B
        @elseif($avg >= 70) C
        @elseif($avg >= 60) D
        @else F
        @endif
    </p>

    <p>Remarks:
        {{ $avg >= 75 ? 'Passed' : 'Failed' }}
    </p>

    <p>Award:
        @if($avg >= 98) With Highest Honors
        @elseif($avg >= 95) With High Honors
        @elseif($avg >= 90) With Honors
        @else No Award
        @endif
    </p>

@endif

</body>
</html>
