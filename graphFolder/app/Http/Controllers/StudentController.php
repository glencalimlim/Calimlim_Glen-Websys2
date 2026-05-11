<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    private array $courses = [
        'BS Computer Science',
        'BS Information Technology',
        'BS Computer Engineering',
        'BS Data Science',
        'BS Cybersecurity',
    ];

    private array $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('student_id', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                      ->orWhere('section', 'like', "%{$request->search}%");
            })
            ->when($request->course, fn($query) => $query->where('course', $request->course))
            ->when($request->year, fn($query) => $query->where('year_level', $request->year))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'courses'  => $this->courses,
            'years'    => $this->years,
            'filters'  => $request->only(['search', 'course', 'year']),
        ]);
    }

    // ── CREATE ────────────────────────────────────────────
    public function create()
    {
        return view('students.form', [
            'student' => null,
            'courses' => $this->courses,
            'years'   => $this->years,
        ]);
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email',
            'course'     => 'required|string',
            'year_level' => 'required|string',
            'section'    => 'required|string|max:50',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    // ── SHOW QR ───────────────────────────────────────────
    public function show(Student $student)
    {
        return view('students.qr', compact('student'));
    }

    // ── EDIT ──────────────────────────────────────────────
    public function edit(Student $student)
    {
        return view('students.form', [
            'student' => $student,
            'courses' => $this->courses,
            'years'   => $this->years,
        ]);
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email,' . $student->id,
            'course'     => 'required|string',
            'year_level' => 'required|string',
            'section'    => 'required|string|max:50',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    // ── DELETE ────────────────────────────────────────────
    public function destroy(Student $student)
    {
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}