<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    protected $fillable = [
        'subject_code',
        'student_id',
        'teacher_id',
    ];

    public function student()
    {
        return $this->belongsTo('App\User', 'student_id');
    }
}
