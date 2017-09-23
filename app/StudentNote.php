<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentNote extends Model
{

    protected $fillable = [
        'subject_code',
        'notes',
    ];
}
