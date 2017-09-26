<?php

use App\StudentGrade;
use App\StudentNote;
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

        $teacher = new User;
        $teacher->first_name = 'Ricardo';
        $teacher->last_name = 'Sanchez';
        $teacher->email = 'teacher@teacher.com';
        $teacher->password = \Hash::make('admin');
        $teacher->type = 'teacher';
        $teacher->save();

        $subject_sched = new SubjectSchedule;
        $subject_sched->subject_code = 'ENGL01';
        $subject_sched->subject_name = 'ENGLISH 1';
        $subject_sched->day = 'monday';
        $subject_sched->time_start = '9:00 AM';
        $subject_sched->time_end = '1:00 PM';
        $subject_sched->teacher_id = $teacher->id;
        $subject_sched->save();

        $subject_sched = new SubjectSchedule;
        $subject_sched->subject_code = 'Filipino1';
        $subject_sched->subject_name = 'fili';
        $subject_sched->day = 'tuesday';
        $subject_sched->time_start = '9:00 AM';
        $subject_sched->time_end = '1:00 PM';
        $subject_sched->teacher_id = $teacher->id;
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
        $subject_stud->teacher_id = $teacher->id;
        $subject_stud->save();

        $subject_studs = new StudentGrade;
        $subject_studs->subject_code = $subject_stud->subject_code;
        $subject_studs->student_id = $user->id;
        $subject_studs->prelim_quiz_grade = '95';
        $subject_studs->prelim_exam_grade = '75';
        $subject_studs->prelim_final_grade = '90';
        $subject_studs->save();

        $note = new StudentNote;
        $note->teacher_id = $teacher->id;
        $note->subject_code = 'ENGL01';
        $note->notes = 'Wuzzup! Wuzzzup!';
        $note->save();
    }
}
