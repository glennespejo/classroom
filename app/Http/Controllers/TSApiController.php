<?php

namespace App\Http\Controllers;

use App\StudentAttendance;
use App\StudentGrade;
use App\StudentNote;
use App\StudentSubject;
use App\SubjectSchedule;
use App\User;
use Illuminate\Http\Request;

class TSApiController extends Controller
{
    public function register(Request $request)
    {

        if (empty($request->all())) {
            return response()->json([
                'error' => 'empty_request',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if (!isset($request->email)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Email already exists.',
            ], 400);
        }
        if ($request->password !== $request->password_confirm) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Password does not match.',
            ], 400);
        }
        $password = \Hash::make($request->password);
        $data = $request->all();
        $data['password'] = $password;
        $user = new User;
        $user->fill($data)->save();

        return response()->json($user);
    }

    public function regSubject(Request $request)
    {
        if (empty($request->all())) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }

        if (!isset($request->teacher_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $subj = new SubjectSchedule;
        $subj->fill($request->all())->save();

        return response()->json($subj);
    }

    public function getSchedules(Request $request)
    {
        if (!isset($request->teacher_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = User::find($request->teacher_id);
        if (empty($user)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'User does not exist.',
            ], 404);
        }
        if ($user->type === 'teacher') {
            $subjects = SubjectSchedule::where('teacher_id', $request->teacher_id)->get();
        } else {
            $subjects = [];
            $subjs = StudentSubject::where('student_id', $request->teacher_id)->get();
            foreach ($subjs as $subj) {
                $subje = SubjectSchedule::where('subject_code', $subj->subject_code)->first();
                $grades = StudentGrade::where('subject_code', $subj->subject_code)->where('student_id', $subj->student_id)->first();
                if (empty($grades)) {
                    $subjects[] = $subje;
                    continue;
                }
                $grades = [
                    'prelim_quiz_grade' => $grades->prelim_quiz_grade,
                    'prelim_exam_grade' => $grades->prelim_exam_grade,
                    'prelim_final_grade' => $grades->prelim_final_grade,
                    'midterm_quiz_grade' => $grades->midterm_quiz_grade,
                    'midterm_exam_grade' => $grades->midterm_exam_grade,
                    'midterm_final_grade' => $grades->midterm_final_grade,
                    'finals_quiz_grade' => $grades->finals_quiz_grade,
                    'finals_exam_grade' => $grades->finals_exam_grade,
                    'finals_final_grade' => $grades->finals_final_grade,
                    'total' => $grades->total,
                ];
                $subje['grades'] = $grades;
                $subjects[] = $subje;

            }
        }
        return response()->json($subjects);
    }

    public function getClassroom(Request $request)
    {
        if (!isset($request->subject_code) || !isset($request->user_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $class = StudentSubject::where('subject_code', $request->subject_code)->first();

        if (empty($class)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Class does not exist.',
            ], 404);
        }

        $user = User::find($request->user_id);

        if ($user->type === "teacher") {

            $results = StudentSubject::where('teacher_id', $user->id)->where('subject_code', $request->subject_code)->get();
            $datas = [];
            foreach ($results as $key => $result) {
                $grades = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $result->student_id)->first();
                $stud = User::find($result->student_id);
                $data = [
                    'id' => $stud->id,
                    'first_name' => $stud->first_name,
                    'last_name' => $stud->last_name,
                    'prelim_quiz_grade' => $grades->prelim_quiz_grade,
                    'prelim_exam_grade' => $grades->prelim_exam_grade,
                    'prelim_final_grade' => $grades->prelim_final_grade,
                    'midterm_quiz_grade' => $grades->midterm_quiz_grade,
                    'midterm_exam_grade' => $grades->midterm_exam_grade,
                    'midterm_final_grade' => $grades->midterm_final_grade,
                    'finals_quiz_grade' => $grades->finals_quiz_grade,
                    'finals_exam_grade' => $grades->finals_exam_grade,
                    'finals_final_grade' => $grades->finals_final_grade,
                    'total' => $grades->total,
                ];
                $datas[$key] = $data;
            }
            return response()->json($datas);

        } else {
            $notes = StudentNote::where('subject_code', $request->subject_code)->get();
            return response()->json($notes);
        }

    }

    public function getGrades(Request $request)
    {
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Student is not enrolled.',
            ], 404);
        }
        return response()->json($user);
    }

    public function addGrades(Request $request)
    {
        if (empty($request->all())) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if ($user) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Grade exists.',
            ], 404);
        }

        $grade = new StudentGrade;
        $grade->fill($request->all())->save();

        return response()->json($grade);

    }

    public function updateGrades(Request $request)
    {

        if (empty($request->all())) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Student is not enrolled.',
            ], 404);
        }
        $grades = StudentGrade::find($user->id);

        $grades->fill($request->all())->save();

        $result = User::find($request->student_id);
        $data = [
            'id' => $result->id,
            'first_name' => $result->first_name,
            'last_name' => $result->last_name,
            'prelim_quiz_grade' => $grades->prelim_quiz_grade,
            'prelim_exam_grade' => $grades->prelim_exam_grade,
            'prelim_final_grade' => $grades->prelim_final_grade,
            'midterm_quiz_grade' => $grades->midterm_quiz_grade,
            'midterm_exam_grade' => $grades->midterm_exam_grade,
            'midterm_final_grade' => $grades->midterm_final_grade,
            'finals_quiz_grade' => $grades->finals_quiz_grade,
            'finals_exam_grade' => $grades->finals_exam_grade,
            'finals_final_grade' => $grades->finals_final_grade,
            'total' => $grades->total,
        ];
        return response()->json($data);
    }

    public function addNote(Request $request)
    {
        if (empty($request->all()) || !isset($request->subject_code)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $note = new StudentNote;

        $note->fill($request->all())->save();

        return response()->json($note);
    }

    public function addStudentSubject(Request $request)
    {
        if (empty($request->all()) || !isset($request->subject_code) || !isset($request->student_id) || !isset($request->teacher_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if ($request->teacher_id === $request->student_id) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'User id is not allowed to enroll to oneself.',
            ], 422);
        }
        $stud = StudentSubject::where('subject_code', $request->subject_code)
            ->where('student_id', $request->student_id)
            ->where('teacher_id', $request->teacher_id)->first();
        if ($stud) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Student is already registered.',
            ], 400);
        }
        $subj = SubjectSchedule::where('subject_code', $request->subject_code)->where('teacher_id', $request->teacher_id)->first();
        if (empty($subj)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Subject does not exist.',
            ], 404);
        }
        if ((int) $request->teacher_id != $request->teacher_id) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid teacher id.',
            ], 404);
        }
        $teach = User::find($request->teacher_id);
        if (empty($teach)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Teacher does not exist.',
            ], 404);
        }
        if ($teach->type !== "teacher") {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'User is not a teacher.',
            ], 400);
        }
        $stud_1 = new StudentSubject;
        $stud_1->subject_code = $request->subject_code;
        $stud_1->student_id = $request->student_id;
        $stud_1->teacher_id = $request->teacher_id;
        $stud_1->save();

        $stud_grad = new StudentGrade;
        $stud_grad->subject_code = $request->subject_code;
        $stud_grad->student_id = $request->student_id;
        $stud_grad->save();

        $subje = SubjectSchedule::where('subject_code', $stud_1->subject_code)->where('teacher_id', $stud_1->teacher_id)->first();
        $grades = StudentGrade::where('subject_code', $stud_1->subject_code)->where('student_id', $request->student_id)->first();
        $grades = [
            'prelim_quiz_grade' => $grades->prelim_quiz_grade,
            'prelim_exam_grade' => $grades->prelim_exam_grade,
            'prelim_final_grade' => $grades->prelim_final_grade,
            'midterm_quiz_grade' => $grades->midterm_quiz_grade,
            'midterm_exam_grade' => $grades->midterm_exam_grade,
            'midterm_final_grade' => $grades->midterm_final_grade,
            'finals_quiz_grade' => $grades->finals_quiz_grade,
            'finals_exam_grade' => $grades->finals_exam_grade,
            'finals_final_grade' => $grades->finals_final_grade,
            'total' => $grades->total,
        ];
        $subje['grades'] = $grades;
        return response()->json($subje);
    }

    public function editSubject(Request $request)
    {
        if (empty($request->all()) || !isset($request->id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $stud = SubjectSchedule::find($request->id);
        if (empty($stud)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }
        $stud->fill($request->all())->save();

        return response()->json($stud);

    }

    public function getSubjectSpec(Request $request)
    {
        if (empty($request->all()) || !isset($request->sched_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $stud = SubjectSchedule::find($request->sched_id);

        if (empty($stud)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Invalid request.',
            ], 400);
        }

        return response()->json($stud);
    }

    public function getNotes(Request $request)
    {
        if (empty($request->all()) || !isset($request->subject_code) || !isset($request->teacher_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $notes = StudentNote::where('subject_code', $request->subject_code)->where('teacher_id', $request->teacher_id)->orderBy('created_at', 'desc')->get();
        $datas = [];
        foreach ($notes as $key => $note) {
            $time = $note->created_at;
            $datas[] = [
                'id' => $note->id,
                'subject_code' => $note->subject_code,
                'teacher_id' => $note->teacher_id,
                'notes' => $note->notes,
                'month' => $time->format('M'),
                'day' => $time->format('j'),
                'year' => $time->format('Y'),
                'time' => $time->format('g:i a'),
            ];
        }

        return response()->json($datas);
    }

    public function updateNotes(Request $request)
    {
        if (empty($request->all()) || !isset($request->id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $note = StudentNote::find($request->id);

        $note->fill($request->all())->save();

        return response()->json($note);
    }

    public function delNote(Request $request)
    {

        if (empty($request->all()) || !isset($request->id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $not = StudentNote::find($request->id);
        if (empty($not)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $not->delete();
        return;
    }

    public function attendance(Request $request)
    {

        if (isset($request->date) && isset($request->subject_code) && isset($request->id)) {
            $attendance = StudentAttendance::where('subject_code', $request->subject_code)
                ->where('teacher_id', $request->id)
                ->where('date', $request->date)
                ->get();
            $attendances = [];
            foreach ($attendance as $key => $value) {
                $attendances[] = $value->student_id;
            }
            $datas = [];
            $data = [];
            $class = StudentSubject::where('subject_code', $request->subject_code)->where('teacher_id', $request->id)->first();
            $results = StudentSubject::where('teacher_id', $request->id)->where('subject_code', $request->subject_code)->get();
            foreach ($results as $key => $res) {
                $value = User::find($res->student_id);
                $data['id'] = $value->id;
                $data['student_name'] = $value->first_name . " " . $value->last_name;
                if (in_array($value->id, $attendances)) {
                    $data['status'] = 'absent';
                    $data['absent'] = true;
                } else {
                    $data['status'] = 'present';
                    $data['absent'] = false;
                }
                $datas[] = $data;
            }
            return response()->json($datas);
        }
        if (empty($request->all()) || !isset($request->subject_code) || !isset($request->student_id) || !isset($request->teacher_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }

        $fromdate = new \DateTime($request->date . '00:00:00');
        $todate = new \DateTime($request->date . '23:59:59');

        $attendance = StudentAttendance::where('subject_code', $request->subject_code)
            ->where('teacher_id', $request->teacher_id)
            ->where('student_id', $request->student_id)
            ->where('date', $request->date)
            ->first();

        if ($attendance) {
            StudentAttendance::find($attendance->id)->delete();

            $attendance = StudentAttendance::where('subject_code', $request->subject_code)
                ->where('teacher_id', $request->teacher_id)
                ->where('date', $request->date)
                ->get();
            $attendances = [];
            foreach ($attendance as $key => $value) {
                $attendances[] = $value->student_id;
            }
            $datas = [];
            $data = [];
            $class = StudentSubject::where('subject_code', $request->subject_code)->where('teacher_id', $request->teacher_id)->first();
            $results = StudentSubject::where('teacher_id', $request->teacher_id)->where('subject_code', $request->subject_code)->get();
            foreach ($results as $key => $res) {
                $value = User::find($res->student_id);
                $data['id'] = $value->id;
                $data['student_name'] = $value->first_name . " " . $value->last_name;
                if (in_array($value->id, $attendances)) {
                    $data['status'] = 'absent';
                    $data['absent'] = true;
                } else {
                    $data['status'] = 'present';
                    $data['absent'] = false;
                }
                $datas[] = $data;
            }
            return response()->json($datas);
        }

        $student = new StudentAttendance;
        $student->fill($request->all())->save();

        $attendance = StudentAttendance::where('subject_code', $request->subject_code)
            ->where('teacher_id', $request->teacher_id)
            ->where('date', $request->date)
            ->get();
        $attendances = [];
        foreach ($attendance as $key => $value) {
            $attendances[] = $value->student_id;
        }
        $datas = [];
        $data = [];
        $class = StudentSubject::where('subject_code', $request->subject_code)->where('teacher_id', $request->teacher_id)->first();
        $results = $class->student()->get();
        foreach ($results as $key => $value) {
            $data['id'] = $value->id;
            $data['student_name'] = $value->first_name . " " . $value->last_name;
            if (in_array($value->id, $attendances)) {
                $data['status'] = 'absent';
                $data['absent'] = true;
            } else {
                $data['status'] = 'present';
                $data['absent'] = false;
            }
            $datas[] = $data;
        }
        return response()->json($datas);

    }

}
