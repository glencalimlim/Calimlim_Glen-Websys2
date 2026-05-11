@extends('students.layout')
@section('title', 'QR Code — ' . $student->name)

@section('content')

<style>

/* =========================
   QR PAGE LAYOUT
========================= */

.qr-page{
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:30px;
    align-items:start;
}

/* =========================
   ID CARD
========================= */

.id-card{
    background:white;
    border-radius:28px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(15,23,42,.08);
    border:1px solid #e2e8f0;
    animation:fadeCard .4s ease;
}

@keyframes fadeCard{
    from{ opacity:0; transform:translateY(10px); }
    to{ opacity:1; transform:translateY(0); }
}

.id-card-header{
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    padding:24px 28px;
    color:white;
    display:flex;
    align-items:center;
    gap:16px;
}

.id-card-logo{
    width:54px;
    height:54px;
    border-radius:16px;
    background:rgba(255,255,255,.18);
    display:flex;
    align-items:center;
    justify-content:center;
}

.id-card-school{
    font-size:1.2rem;
    font-weight:800;
}

.id-card-type{
    opacity:.85;
    font-size:.9rem;
    margin-top:3px;
}

/* BODY */

.id-card-body{
    display:grid;
    grid-template-columns:180px 1fr 220px;
    gap:28px;
    padding:30px;
    align-items:center;
}

/* PHOTO */

.id-card-photo-col{
    text-align:center;
}

.id-card-photo{
    width:160px;
    height:160px;
    object-fit:cover;
    border-radius:24px;
    border:5px solid #eef2ff;
    box-shadow:0 10px 25px rgba(15,23,42,.08);
}

.id-card-year-badge{
    display:inline-block;
    margin-top:14px;
    background:#eef2ff;
    color:#4f46e5;
    font-weight:700;
    padding:8px 16px;
    border-radius:999px;
    font-size:.85rem;
}

/* INFO */

.id-card-name{
    font-size:2rem;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

.id-card-number{
    color:#6366f1;
    font-weight:700;
    margin-bottom:18px;
}

.id-card-table{
    width:100%;
    border-collapse:collapse;
}

.id-card-table tr td{
    padding:10px 0;
    border-bottom:1px solid #e2e8f0;
    font-size:.95rem;
}

.id-card-table tr td:first-child{
    color:#64748b;
    width:110px;
    font-weight:600;
}

.id-card-table tr td:last-child{
    font-weight:600;
    color:#0f172a;
}

/* QR */

.id-card-qr-col{
    text-align:center;
}

.id-card-qr{
    width:180px;
    height:180px;
    padding:12px;
    border-radius:22px;
    border:1px solid #e2e8f0;
    box-shadow:0 10px 20px rgba(15,23,42,.05);
    background:white;
}

.qr-hint{
    margin-top:12px;
    color:#64748b;
    font-size:.9rem;
}

/* FOOTER */

.id-card-footer{
    background:#f8fafc;
    padding:18px;
    text-align:center;
    color:#64748b;
    font-size:.85rem;
    border-top:1px solid #e2e8f0;
}

/* =========================
   DETAILS PANEL
========================= */

.qr-details{
    background:white;
    border-radius:28px;
    padding:28px;
    box-shadow:0 15px 40px rgba(15,23,42,.08);
    border:1px solid #e2e8f0;
}

.qr-details-title{
    font-size:1.3rem;
    font-weight:800;
    margin-bottom:24px;
}

.detail-row{
    display:flex;
    justify-content:space-between;
    padding:14px 0;
    border-bottom:1px solid #e2e8f0;
    gap:20px;
}

.detail-label{
    color:#64748b;
    font-weight:600;
}

.detail-value{
    color:#0f172a;
    font-weight:700;
    text-align:right;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:1100px){
    .qr-page{
        grid-template-columns:1fr;
    }

    .id-card-body{
        grid-template-columns:1fr;
        text-align:center;
    }

    .detail-row{
        flex-direction:column;
        align-items:flex-start;
    }

    .detail-value{
        text-align:left;
    }
}

</style>

@php
    // SAFE fallback to avoid undefined variable crash
    $qrData = urlencode($student->qrPayload());
    $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={$qrData}&bgcolor=ffffff&color=111827&qzone=2";
@endphp

<div class="page-header">

    <div>
        <h1 class="page-title">Student QR Code</h1>
        <p class="page-sub">Scannable ID card for {{ $student->name }}</p>
    </div>

    <div style="display:flex;gap:10px">

        <a href="{{ route('students.edit', $student->id ?? $student) }}"
           class="btn btn-secondary">
            Edit Student
        </a>

        <a href="{{ route('students.index') }}"
           class="btn btn-ghost">
            ← Back
        </a>

    </div>

</div>

<div class="qr-page">

    <!-- ID CARD -->
    <div class="id-card">

        <div class="id-card-header">

            <div class="id-card-logo">
                <svg width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
            </div>

            <div>
                <div class="id-card-school">EduTrack University</div>
                <div class="id-card-type">Student Identification Card</div>
            </div>

        </div>

        <div class="id-card-body">

            <div class="id-card-photo-col">
                <img src="{{ $student->photoUrl() }}"
                     alt="{{ $student->name }}"
                     class="id-card-photo">

                <span class="id-card-year-badge">
                    {{ $student->year_level }}
                </span>
            </div>

            <div>

                <h2 class="id-card-name">
                    {{ $student->name }}
                </h2>

                <div class="id-card-number">
                    {{ $student->student_id }}
                </div>

                <table class="id-card-table">
                    <tr><td>Course</td><td>{{ $student->course }}</td></tr>
                    <tr><td>Section</td><td>{{ $student->section }}</td></tr>
                    <tr><td>Email</td><td>{{ $student->email }}</td></tr>
                </table>

            </div>

            <div class="id-card-qr-col">

                <img src="{{ $qrUrl }}"
                     class="id-card-qr"
                     alt="QR Code">

                <p class="qr-hint">Scan to verify</p>

            </div>

        </div>

        <div class="id-card-footer">
            Valid for Academic Year 2025–2026 | Do not alter or reproduce
        </div>

    </div>

    <!-- DETAILS -->
    <div class="qr-details">

        <h3 class="qr-details-title">Student Information</h3>

        @foreach([
            ['Student ID', $student->student_id],
            ['Full Name',  $student->name],
            ['Course',     $student->course],
            ['Year Level', $student->year_level],
            ['Section',    $student->section],
            ['Email',      $student->email],
        ] as [$label, $value])

        <div class="detail-row">
            <span class="detail-label">{{ $label }}</span>
            <span class="detail-value">{{ $value }}</span>
        </div>

        @endforeach

        <div style="margin-top:24px;display:flex;gap:12px;flex-wrap:wrap">

            <a href="{{ $qrUrl }}"
               download="qr_{{ $student->student_id }}.png"
               class="btn btn-primary">
                Download QR
            </a>

            <a href="{{ route('students.edit', $student->id ?? $student) }}"
               class="btn btn-secondary">
                Edit Record
            </a>

        </div>

    </div>

</div>

@endsection