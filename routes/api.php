<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('subject-schedules', 'SubjectScheduleController');
Route::resource('student-subjects', 'StudentSubjectController');
Route::resource('student-attendances', 'StudentAttendaceController');
Route::resource('student-grades', 'StudentGradeController');
//register Schedule pass teacher_id
Route::post('/register/subject', 'TSApiController@regSubject');
// pass
Route::post('/edit/subject', 'TSApiController@editSubject');
// pass id
Route::get('/edit/subject', 'TSApiController@getSubjectSpec');
// get classroom pass subject code
Route::get('/get/classroom', 'TSApiController@getClassroom');
//pass teacher_id
Route::get('/get/schedule', 'TSApiController@getSchedules');
// get grade pass subject_code and student_id
Route::get('/student/grade', 'TSApiController@getGrades');
// add grade pass subject_code and student_id
Route::post('/add/grade', 'TSApiController@addGrades');
// update grade pass subject_code and student_idssss
Route::patch('/update/grade', 'TSApiController@updateGrades');
// pass subject_code
Route::post('/add/note', 'TSApiController@addNote');
// pass id
Route::post('/update/note', 'TSApiController@updateNotes');

Route::delete('/destroy/note', 'TSApiController@delNote');

Route::get('/get/notes', 'TSApiController@getNotes');

Route::post('/enroll/subject', 'TSApiController@addStudentSubject');

Route::post('/attendance', 'TSApiController@attendance');

Route::post('/register', 'TSApiController@register');

Route::post('/login', 'LoginController@loginApi');
