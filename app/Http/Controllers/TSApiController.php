<?php

namespace App\Http\Controllers;

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

        $user = SubjectSchedule::where('teacher_id', $request->teacher_id)->get();

        if (empty($user)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'User does not exist.',
            ], 404);
        }

        return response()->json($user);
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

            $results = $class->student()->get();

            return response()->json($results);

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
        $grade = StudentGrade::find($user->id);

        $grade->fill($request->all())->save();

        return response()->json($grade);
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

        $note->fill($request->all)->save();

        return response()->json($note);
    }

    public function addStudentSubject(Request $request)
    {
        if (empty($request->all()) || !isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $stud = StudentSubject::where('subject_code', $request->subject_code)->first();
        if ($stud) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Student is already registered.',
            ], 400);
        }

        $stud = new StudentSubject;
        $stud->subject_code = $request->subject_code;
        $stud->student_id = $request->student_id;
        $stud->save();

        return response()->json($stud);
    }

    public function editSubject(Request $request)
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
        $stud->fill($request->all())->save();

        return response()->json($stud);

    }

}
