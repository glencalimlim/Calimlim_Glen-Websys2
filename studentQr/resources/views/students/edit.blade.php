@extends('layouts.app')

@section('content')

<div class="card p-4">

<h3 class="fw-bold mb-4">Edit Student</h3>

<form method="POST" action="{{ route('students.update', $student->id) }}">
@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Student ID</label>
<input type="text" name="student_id" class="form-control"
value="{{ $student->student_id }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Course</label>
<input type="text" name="course" class="form-control"
value="{{ $student->course }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">First Name</label>
<input type="text" name="first_name" class="form-control"
value="{{ $student->first_name }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Last Name</label>
<input type="text" name="last_name" class="form-control"
value="{{ $student->last_name }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Middle Name</label>
<input type="text" name="middle_name" class="form-control"
value="{{ $student->middle_name }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Year Level</label>
<input type="text" name="year_level" class="form-control"
value="{{ $student->year_level }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Section</label>
<input type="text" name="section" class="form-control"
value="{{ $student->section }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Contact</label>
<input type="text" name="contact" class="form-control"
value="{{ $student->contact }}">
</div>

<div class="col-md-12 mb-3">
<label class="form-label">Address</label>
<input type="text" name="address" class="form-control"
value="{{ $student->address }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="{{ $student->email }}">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Birthday</label>
<input type="date" name="birthday" class="form-control"
value="{{ $student->birthday }}">
</div>

</div>

<button class="btn btn-warning">Update Student</button>

<a href="{{ route('students.index') }}" class="btn btn-secondary">
Back
</a>

</form>

</div>

@endsection