<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::where('student_id','like',"%$search%")
        ->orWhere('first_name','like',"%$search%")
        ->orWhere('last_name','like',"%$search%")
        ->orWhere('course','like',"%$search%")
        ->get();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        Student::create($request->all());

        return redirect()->route('students.index');
    }

    public function show(Student $student)
    {
        $qr = QrCode::size(250)->generate(json_encode($student));

        return view('students.show', compact('student','qr'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $student->update($request->all());

        return redirect()->route('students.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index');
    }
}