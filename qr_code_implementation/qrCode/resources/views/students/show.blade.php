@extends('layouts.app')

@section('content')

<div class="card p-5 text-center">

<h2 class="fw-bold mb-3">
{{ $student->first_name }} {{ $student->last_name }}
</h2>

<p class="text-muted">
{{ $student->course }} | Year {{ $student->year_level }}
</p>

<div class="mb-4">
{!! $qr !!}
</div>

<a href="/students" class="btn btn-primary">
Back
</a>

</div>

@endsection