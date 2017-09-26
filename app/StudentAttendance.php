<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = [
        'subject_code',
        'student_id',
        'status',
    ];
}
