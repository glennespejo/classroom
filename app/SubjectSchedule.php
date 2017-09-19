<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectSchedule extends Model
{
    protected $fillable = [
        'subject_code',
        'subject_name',
        'day',
        'time_start',
        'time_end',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id');
    }
}
