@extends('layouts.app')

@section('content')

<div class="card p-4">

<h3 class="fw-bold mb-4">Add Student</h3>

<form method="POST" action="{{ route('students.store') }}">
@csrf

<div class="row">

<div class="col-md-6 mb-3">
<input class="form-control" name="student_id" placeholder="Student ID">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="course" placeholder="Course">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="first_name" placeholder="First Name">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="last_name" placeholder="Last Name">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="year_level" placeholder="Year Level">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="section" placeholder="Section">
</div>

<div class="col-md-12 mb-3">
<input class="form-control" name="address" placeholder="Address">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="contact" placeholder="Contact">
</div>

<div class="col-md-6 mb-3">
<input class="form-control" name="email" placeholder="Email">
</div>

<div class="col-md-12 mb-3">
<input class="form-control" type="date" name="birthday">
</div>

</div>

<button class="btn btn-primary">Save Student</button>

<a href="/students" class="btn btn-secondary">Back</a>

</form>

</div>

@endsection