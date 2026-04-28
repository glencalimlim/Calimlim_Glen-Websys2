@extends('layouts.app')

@section('content')

<div class="card p-4">

<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold">Student Records</h3>

    <a href="{{ route('students.create') }}" class="btn btn-primary">
        + Add Student
    </a>
</div>

<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="search" class="form-control" placeholder="Search student...">
<button class="btn btn-success">Search</button>
</div>
</form>

<table class="table table-hover text-center align-middle">
<tr class="table-primary">
<th>ID</th>
<th>Name</th>
<th>Course</th>
<th>Year</th>
<th>Actions</th>
</tr>

@foreach($students as $student)
<tr>
<td>{{ $student->student_id }}</td>
<td>{{ $student->first_name }} {{ $student->last_name }}</td>
<td>{{ $student->course }}</td>
<td>{{ $student->year_level }}</td>

<td>

<a href="{{ route('students.show',$student->id) }}" class="btn btn-info btn-sm">QR</a>

<a href="{{ route('students.edit',$student->id) }}" class="btn btn-warning btn-sm">Edit</a>

<form action="{{ route('students.destroy',$student->id) }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm">
Delete
</button>

</form>

</td>
</tr>
@endforeach

</table>

</div>

@endsection