<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
   protected $fillable = [
'student_id',
'first_name',
'last_name',
'middle_name',
'course',
'year_level',
'section',
'address',
'contact',
'email',
'birthday'
];
}
