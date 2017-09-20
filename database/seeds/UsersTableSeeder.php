<?php

use App\StudentGrade;
use App\StudentSubject;
use App\SubjectSchedule;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->first_name = 'Admin';
        $user->last_name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = \Hash::make('admin');
        $user->type = 'admin';
        $user->save();

        $user = new User;
        $user->first_name = 'Ricardo';
        $user->last_name = 'Sanchez';
        $user->email = 'teacher@teacher.com';
        $user->password = \Hash::make('admin');
        $user->type = 'teacher';
        $user->save();

        $subject_sched = new SubjectSchedule;
        $subject_sched->subject_code = 'ENGL01';
        $subject_sched->subject_name = 'ENGLISH 1';
        $subject_sched->day = 'monday';
        $subject_sched->time_start = '9:00 AM';
        $subject_sched->time_end = '1:00 PM';
        $subject_sched->teacher_id = $user->id;
        $subject_sched->save();

        $user = new User;
        $user->first_name = 'Jessica';
        $user->last_name = 'Sogo';
        $user->email = 'student@student.com';
        $user->password = \Hash::make('admin');
        $user->type = 'student';
        $user->save();

        $subject_stud = new StudentSubject;
        $subject_stud->subject_code = 'ENGL01';
        $subject_stud->student_id = $user->id;
        $subject_stud->save();

        $subject_stud = new StudentGrade;
        $subject_stud->student_schedule_id = $subject_stud->id;
        $subject_stud->prelim_quiz_grade = '95';
        $subject_stud->prelim_exam_grade = '75';
        $subject_stud->prelim_final_grade = '90';
        $subject_stud->save();
    }
}
