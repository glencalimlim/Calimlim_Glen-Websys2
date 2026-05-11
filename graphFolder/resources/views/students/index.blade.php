@extends('students.layout')
@section('title', 'Students')

@section('content')

<style>

/* =========================================================
   INDEX PAGE STYLES
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
    margin:0;
}

.page-sub{
    margin-top:6px;
    color:#64748b;
    font-size:.95rem;
}

/* =========================================================
   TOOLBAR
========================================================= */

.toolbar{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
    background:white;
    padding:18px;
    border-radius:24px;
    margin-bottom:30px;
    box-shadow:0 10px 25px rgba(15,23,42,.06);
}

.search-wrap{
    position:relative;
    flex:1;
    min-width:260px;
}

.search-icon{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    color:#94a3b8;
}

.search-input{
    width:100%;
    padding:14px 14px 14px 42px;
    border-radius:16px;
    border:1px solid #e2e8f0;
    outline:none;
    transition:all .25s ease;
    font-size:.92rem;
}

.search-input:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 4px rgba(99,102,241,.12);
}

.filter-select{
    padding:14px 16px;
    border-radius:16px;
    border:1px solid #e2e8f0;
    background:white;
    outline:none;
    min-width:160px;
    font-size:.92rem;
}

.filter-select:focus{
    border-color:#6366f1;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state{
    background:white;
    border-radius:30px;
    padding:70px 30px;
    text-align:center;
    box-shadow:0 10px 25px rgba(15,23,42,.06);
}

.empty-icon{
    font-size:4rem;
    margin-bottom:20px;
}

.empty-state h3{
    font-size:1.5rem;
    margin-bottom:10px;
}

.empty-state p{
    color:#64748b;
}

/* =========================================================
   STUDENT GRID
========================================================= */

.student-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:26px;
}

.student-card{
    background:white;
    border-radius:28px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    transition:all .3s ease;
    border:1px solid #eef2ff;
}

.student-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(15,23,42,.12);
}

/* CARD TOP */

.card-top{
    position:relative;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    padding:28px;
    display:flex;
    justify-content:center;
}

.card-photo{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid rgba(255,255,255,.35);
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.year-badge{
    position:absolute;
    top:18px;
    right:18px;
    background:rgba(255,255,255,.18);
    color:white;
    padding:8px 14px;
    border-radius:999px;
    font-size:.8rem;
    font-weight:700;
    backdrop-filter:blur(10px);
}

/* CARD BODY */

.card-body{
    padding:26px;
    text-align:center;
}

.card-name{
    font-size:1.3rem;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

.card-id{
    color:#6366f1;
    font-weight:700;
    margin-bottom:6px;
}

.card-course{
    font-weight:600;
    color:#334155;
    margin-bottom:12px;
}

.card-meta{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    color:#64748b;
    font-size:.88rem;
}

.dot{
    opacity:.5;
}

/* =========================================================
   ACTIONS
========================================================= */

.card-actions{
    display:flex;
    justify-content:space-between;
    gap:10px;
    padding:0 20px 22px;
}

.inline-form{
    flex:1;
}

.action-btn{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:12px;
    border-radius:14px;
    font-weight:700;
    text-decoration:none;
    border:none;
    cursor:pointer;
    transition:all .25s ease;
    font-size:.85rem;
}

.action-btn.qr{
    background:#eef2ff;
    color:#4f46e5;
}

.action-btn.edit{
    background:#ecfeff;
    color:#0891b2;
}

.action-btn.delete{
    background:#fef2f2;
    color:#dc2626;
    width:100%;
}

.action-btn:hover{
    transform:translateY(-2px);
}

/* =========================================================
   PAGINATION WRAP
========================================================= */

.pagination-wrap{
    margin-top:35px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:768px){

    .student-grid{
        grid-template-columns:1fr;
    }

    .toolbar{
        flex-direction:column;
        align-items:stretch;
    }

    .card-actions{
        flex-direction:column;
    }

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }
}

</style>

<!-- PAGE HEADER -->
<div class="page-header">

    <div>
        <h1 class="page-title">Student Records</h1>
        <p class="page-sub">
            {{ $students->total() }} students registered
        </p>
    </div>

    <a href="{{ route('students.create') }}" class="btn btn-primary">

        <svg width="16" height="16"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">

            <circle cx="12" cy="12" r="10"/>
            <path d="M12 8v8M8 12h8"/>

        </svg>

        Add Student

    </a>

</div>

<!-- SEARCH & FILTER -->
<form method="GET"
      action="{{ route('students.index') }}"
      class="toolbar">

    <div class="search-wrap">

        <svg class="search-icon"
             width="16"
             height="16"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">

            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>

        </svg>

        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Search by name, ID, email, section…"
            value="{{ $filters['search'] ?? '' }}"
        >

    </div>

    <select name="course" class="filter-select">

        <option value="">All Courses</option>

        @foreach($courses as $c)

            <option value="{{ $c }}"
                {{ ($filters['course'] ?? '') === $c ? 'selected' : '' }}>
                {{ $c }}
            </option>

        @endforeach

    </select>

    <select name="year" class="filter-select">

        <option value="">All Years</option>

        @foreach($years as $y)

            <option value="{{ $y }}"
                {{ ($filters['year'] ?? '') === $y ? 'selected' : '' }}>
                {{ $y }}
            </option>

        @endforeach

    </select>

    <button type="submit" class="btn btn-secondary">

        <svg width="14"
             height="14"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">

            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>

        </svg>

        Filter

    </button>

    @if(array_filter($filters))
        <a href="{{ route('students.index') }}" class="btn btn-ghost">
            Clear
        </a>
    @endif

</form>

<!-- STUDENT GRID -->

@if($students->isEmpty())

    <div class="empty-state">

        <div class="empty-icon">🎓</div>

        <h3>No students found</h3>

        <p>
            Try adjusting your search or filters,
            or add a new student.
        </p>

        <a href="{{ route('students.create') }}"
           class="btn btn-primary"
           style="margin-top:16px">

            Add First Student

        </a>

    </div>

@else

    <div class="student-grid">

        @foreach($students as $student)

        <div class="student-card">

            <!-- CARD TOP -->
            <div class="card-top">

                <img src="{{ $student->photoUrl() }}"
                     alt="{{ $student->name }}"
                     class="card-photo">

                <span class="year-badge">
                    {{ $student->year_level }}
                </span>

            </div>

            <!-- CARD BODY -->
            <div class="card-body">

                <h3 class="card-name">
                    {{ $student->name }}
                </h3>

                <div class="card-id">
                    {{ $student->student_id }}
                </div>

                <div class="card-course">
                    {{ $student->course }}
                </div>

                <div class="card-meta">

                    <span>{{ $student->section }}</span>

                    <span class="dot">·</span>

                    <span>
                        {{ Str::before($student->email, '@') }}
                    </span>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="card-actions">

                <a href="{{ route('students.show', $student) }}"
                   class="action-btn qr"
                   title="View QR Code">

                    <svg width="16"
                         height="16"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>

                    </svg>

                    QR

                </a>

                <a href="{{ route('students.edit', $student) }}"
                   class="action-btn edit"
                   title="Edit">

                    <svg width="16"
                         height="16"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>

                    </svg>

                    Edit

                </a>

                <form method="POST"
                      action="{{ route('students.destroy', $student) }}"
                      class="inline-form"
                      onsubmit="return confirm('Delete {{ addslashes($student->name) }}? This cannot be undone.')">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="action-btn delete"
                            title="Delete">

                        <svg width="16"
                             height="16"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14H6L5 6"/>
                            <path d="M10 11v6M14 11v6"/>
                            <path d="M9 6V4h6v2"/>

                        </svg>

                        Delete

                    </button>

                </form>

            </div>

        </div>

        @endforeach

    </div>

    <!-- PAGINATION -->
    <div class="pagination-wrap">
        {{ $students->links('students.pagination') }}
    </div>

@endif

@endsection