<?php

namespace App\Http\Controllers;

use App\StudentGrade;
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
                'error' => 'user_email',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'error' => 'user_email',
                'message' => 'Email already exists.',
            ], 400);
        }
        if ($request->password !== $request->password_confirm) {
            return response()->json([
                'error' => 'user_email',
                'message' => 'Password does not match.',
            ], 400);
        }
        $password = \Hash::make($request->all());
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
                'error' => 'empty_request',
                'message' => 'Your request is empty.',
            ], 400);
        }

        if (!isset($request->teacher_id)) {
            return response()->json([
                'error' => 'user_email',
                'message' => 'Invalid request.',
            ], 400);
        }
        $subj = new SubjectSchedule;
        $subj->fill($request->all())->save();

        return response()->json($user);
    }

    public function getSchedules(Request $request)
    {
        if (!isset($request->teacher_id)) {
            return response()->json([
                'error' => 'user_email',
                'message' => 'Invalid request.',
            ], 400);
        }

        $user = SubjectSchedule::where('teacher_id', $request->teacher_id)->get();

        if (empty($user)) {
            return response()->json([
                'error' => 'user_not_found',
                'message' => 'User does not exist.',
            ], 404);
        }

        return response()->json($user);
    }

    public function getClassroom(Request $request)
    {
        if (!isset($request->subject_code)) {
            return response()->json([
                'error' => 'user_email',
                'message' => 'Invalid request.',
            ], 400);
        }

        $class = StudentSubject::where('subject_code', $request->subject_code)->first();

        if (empty($class)) {
            return response()->json([
                'error' => 'class_not_found',
                'message' => 'Class does not exist.',
            ], 404);
        }

        $results = $class->student()->get();

        return response()->json($results);

    }

    public function getGrades(Request $request)
    {
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'student_subject_id',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'student_subject_id',
                'message' => 'Student is not enrolled.',
            ], 404);
        }
        return response()->json($user);
    }

    public function addGrades(Request $request)
    {
        if (empty($request->all())) {
            return response()->json([
                'error' => 'empty_request',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'student_subject_id',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if ($user) {
            return response()->json([
                'error' => 'grade_exist',
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
                'error' => 'empty_request',
                'message' => 'Your request is empty.',
            ], 400);
        }
        if (!isset($request->subject_code) || !isset($request->student_id)) {
            return response()->json([
                'error' => 'student_subject_id',
                'message' => 'Invalid request.',
            ], 400);
        }
        $user = StudentGrade::where('subject_code', $request->subject_code)->where('student_id', $request->student_id)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'student_subject_id',
                'message' => 'Student is not enrolled.',
            ], 404);
        }
        $grade = StudentGrade::find($user->id);

        $grade->fill($request->all())->save();

        return response()->json($grade);
    }
}
