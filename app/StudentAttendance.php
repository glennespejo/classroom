<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = [
        'subject_code',
        'teacher_id',
        'student_id',
        'status',
    ];
}
