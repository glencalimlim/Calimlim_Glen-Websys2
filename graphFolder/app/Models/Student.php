<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'course',
        'year_level',
        'section',
        'photo',
    ];

    public function qrPayload(): string
    {
        return implode("\n", [
            "Student ID : {$this->student_id}",
            "Name       : {$this->name}",
            "Course     : {$this->course}",
            "Year       : {$this->year_level}",
            "Section    : {$this->section}",
            "Email      : {$this->email}",
        ]);
    }

    public function photoUrl(): string
    {
        if ($this->photo && file_exists(storage_path("app/public/{$this->photo}"))) {
            return asset("storage/{$this->photo}");
        }

        $seed = urlencode($this->name);
        return "https://api.dicebear.com/7.x/personas/svg?seed={$seed}";
    }
}