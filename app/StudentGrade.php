<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    protected $fillable = [
        'student_schedule_id',
        'prelim_quiz_grade',
        'prelim_exam_grade',
        'prelim_final_grade',
        'midterm_quiz_grade',
        'midterm_exam_grade',
        'midter_final_grade',
        'finals_quiz_grade',
        'finals_exam_grade',
        'finals_final_grade',
    ];
}
