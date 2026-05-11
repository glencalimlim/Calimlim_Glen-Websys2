@extends('students.layout')
@section('title', $student ? 'Edit Student' : 'Add Student')

@section('content')

<style>

/* =========================================================
   FORM PAGE STYLES
========================================================= */

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.page-title{
    font-size:2rem;
    font-weight:800;
    color:#0f172a;
}

.page-sub{
    margin-top:6px;
    color:#64748b;
}

.form-card{
    background:white;
    border-radius:30px;
    padding:35px;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
}

.student-form{
    display:flex;
    flex-direction:column;
    gap:30px;
}

/* PHOTO */

.photo-section{
    display:flex;
    justify-content:center;
}

.photo-upload-wrap{
    width:230px;
    height:230px;
    border-radius:28px;
    border:2px dashed #cbd5e1;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    overflow:hidden;
    cursor:pointer;
    transition:all .25s ease;
}

.photo-upload-wrap:hover{
    border-color:#6366f1;
    background:#eef2ff;
}

#photoPlaceholder{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
    text-align:center;
    color:#64748b;
    padding:20px;
}

#photoPlaceholder svg{
    color:#6366f1;
}

#photoPreview{
    width:100%;
    height:100%;
    object-fit:cover;
}

.hidden{
    display:none;
}

.file-input{
    display:none;
}

/* FIELDS */

.fields-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:24px;
}

.field-group{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.field-label{
    font-weight:700;
    color:#0f172a;
}

.req{
    color:#dc2626;
}

.field-input{
    width:100%;
    padding:15px 16px;
    border-radius:16px;
    border:1px solid #e2e8f0;
    background:white;
    outline:none;
    transition:all .25s ease;
    font-size:.94rem;
}

.field-input:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 4px rgba(99,102,241,.12);
}

.field-input.is-error{
    border-color:#dc2626;
}

.field-error{
    color:#dc2626;
    font-size:.84rem;
}

/* ACTIONS */

.form-actions{
    display:flex;
    justify-content:flex-end;
    gap:14px;
    flex-wrap:wrap;
}

@media(max-width:768px){

    .form-card{
        padding:24px;
    }

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }
}

</style>

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $student ? 'Edit Student' : 'Add New Student' }}</h1>
        <p class="page-sub">
            {{ $student ? "Updating record for {$student->name}" : 'Fill in the details below' }}
        </p>
    </div>

    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        ← Back to List
    </a>
</div>

<div class="form-card">

    <form
        method="POST"
        action="{{ $student ? route('students.update', $student) : route('students.store') }}"
        enctype="multipart/form-data"
        class="student-form"
    >

        @csrf
        @if($student) @method('PUT') @endif

        <!-- PHOTO -->
        <div class="photo-section">

            <div class="photo-upload-wrap" id="photoWrap">

                <img
                    id="photoPreview"
                    src="{{ $student ? $student->photoUrl() : '' }}"
                    alt="Preview"
                    class="{{ $student ? '' : 'hidden' }}"
                >

                <div id="photoPlaceholder" class="{{ $student ? 'hidden' : '' }}">

                    <svg width="32" height="32" viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5">

                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>

                    <span>Click to upload photo</span>
                    <small>JPG, PNG, WEBP — max 2 MB</small>

                </div>

                <input type="file"
                       name="photo"
                       id="photoInput"
                       accept="image/*"
                       class="file-input">

            </div>

        </div>

        @error('photo')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <!-- FIELDS -->
        <div class="fields-grid">

            <div class="field-group">
                <label class="field-label">
                    Full Name <span class="req">*</span>
                </label>

                <input type="text"
                       name="name"
                       class="field-input @error('name') is-error @enderror"
                       value="{{ old('name', $student?->name) }}"
                       placeholder="e.g. Maria Santos"
                       required>

                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">
                    Student ID <span class="req">*</span>
                </label>

                <input type="text"
                       name="student_id"
                       class="field-input @error('student_id') is-error @enderror"
                       value="{{ old('student_id', $student?->student_id) }}"
                       placeholder="e.g. 2024-00001"
                       required>

                @error('student_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">
                    Email Address <span class="req">*</span>
                </label>

                <input type="email"
                       name="email"
                       class="field-input @error('email') is-error @enderror"
                       value="{{ old('email', $student?->email) }}"
                       placeholder="student@university.edu"
                       required>

                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">
                    Section <span class="req">*</span>
                </label>

                <input type="text"
                       name="section"
                       class="field-input @error('section') is-error @enderror"
                       value="{{ old('section', $student?->section) }}"
                       placeholder="e.g. CS-301"
                       required>

                @error('section')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">
                    Course <span class="req">*</span>
                </label>

                <select name="course"
                        class="field-input @error('course') is-error @enderror"
                        required>

                    @foreach($courses as $c)
                        <option value="{{ $c }}"
                            {{ old('course', $student?->course) === $c ? 'selected' : '' }}>
                            {{ $c }}
                        </option>
                    @endforeach

                </select>

                @error('course')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">
                    Year Level <span class="req">*</span>
                </label>

                <select name="year_level"
                        class="field-input @error('year_level') is-error @enderror"
                        required>

                    @foreach($years as $y)
                        <option value="{{ $y }}"
                            {{ old('year_level', $student?->year_level) === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach

                </select>

                @error('year_level')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="form-actions">

            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">

                <svg width="16" height="16" viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.5">

                    <polyline points="20 6 9 17 4 12"/>
                </svg>

                {{ $student ? 'Save Changes' : 'Add Student' }}

            </button>

        </div>

    </form>

</div>

@push('head')
<script>

document.getElementById('photoWrap').addEventListener('click', () => {
    document.getElementById('photoInput').click();
});

document.getElementById('photoInput').addEventListener('change', function () {

    const file = this.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = e => {

        document.getElementById('photoPreview').src = e.target.result;
        document.getElementById('photoPreview').classList.remove('hidden');
        document.getElementById('photoPlaceholder').classList.add('hidden');

    };

    reader.readAsDataURL(file);

});

</script>
@endpush

@endsection