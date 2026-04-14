<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'student_no'   => 'required|unique:students,student_no',
            'first_name'   => 'required',
            'middle_name'  => 'nullable',
            'last_name'    => 'required',
            'birthdate'    => 'required|date',
            'gender'       => 'required',
            'email'        => 'required|email|unique:students,email',
            'phone'        => 'required',
            'address'      => 'required',
            'course'       => 'required',
            'year_level'   => 'required',
            'username'     => 'required|unique:students,username',
            'password'     => 'required|min:6|confirmed',
        ]);

        $studentId = DB::table('students')->insertGetId([
            'student_no'   => $request->student_no,
            'first_name'   => $request->first_name,
            'middle_name'  => $request->middle_name,
            'last_name'    => $request->last_name,
            'birthdate'    => $request->birthdate,
            'gender'       => $request->gender,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'course'       => $request->course,
            'year_level'   => $request->year_level,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        DB::table('system_logs')->insert([
            'student_id'   => $studentId,
            'event'        => 'Registration',
            'description'  => 'Student registered an account.',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $student = DB::table('students')
            ->where('username', $request->username)
            ->first();

        if ($student && Hash::check($request->password, $student->password)) {
            Session::put('student_id', $student->id);
            Session::put('student_name', $student->first_name . ' ' . $student->last_name);

            DB::table('system_logs')->insert([
                'student_id'   => $student->id,
                'event'        => 'Login',
                'description'  => 'Student logged in.',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Invalid username or password.');
    }

    public function dashboard()
    {
        if (!Session::has('student_id')) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $student = DB::table('students')
            ->where('id', Session::get('student_id'))
            ->first();

        return view('dashboard', compact('student'));
    }

    public function showEditProfile()
    {
        if (!Session::has('student_id')) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $student = DB::table('students')
            ->where('id', Session::get('student_id'))
            ->first();

        return view('edit-profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        if (!Session::has('student_id')) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $studentId = Session::get('student_id');

        $request->validate([
            'student_no'   => 'required|unique:students,student_no,' . $studentId,
            'first_name'   => 'required',
            'middle_name'  => 'nullable',
            'last_name'    => 'required',
            'birthdate'    => 'required|date',
            'gender'       => 'required',
            'email'        => 'required|email|unique:students,email,' . $studentId,
            'phone'        => 'required',
            'address'      => 'required',
            'course'       => 'required',
            'year_level'   => 'required',
            'username'     => 'required|unique:students,username,' . $studentId,
        ]);

        DB::table('students')
            ->where('id', $studentId)
            ->update([
                'student_no'   => $request->student_no,
                'first_name'   => $request->first_name,
                'middle_name'  => $request->middle_name,
                'last_name'    => $request->last_name,
                'birthdate'    => $request->birthdate,
                'gender'       => $request->gender,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'course'       => $request->course,
                'year_level'   => $request->year_level,
                'username'     => $request->username,
                'updated_at'   => now(),
            ]);

        DB::table('system_logs')->insert([
            'student_id'   => $studentId,
            'event'        => 'Profile Update',
            'description'  => 'Student updated profile information.',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect('/dashboard')->with('success', 'Profile updated successfully.');
    }

    public function logout()
    {
        if (Session::has('student_id')) {
            DB::table('system_logs')->insert([
                'student_id'   => Session::get('student_id'),
                'event'        => 'Logout',
                'description'  => 'Student logged out.',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        Session::flush();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    public function logs()
    {
        $logs = DB::table('system_logs')
            ->leftJoin('students', 'system_logs.student_id', '=', 'students.id')
            ->select(
                'system_logs.*',
                'students.first_name',
                'students.last_name',
                'students.username'
            )
            ->orderBy('system_logs.id', 'desc')
            ->get();

        return view('logs', compact('logs'));
    }
}