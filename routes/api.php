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
// get classroom pass subject code
Route::get('/get/classroom', 'TSApiController@getClassroom');
//pass teacher_id
Route::get('/get/schedule', 'TSApiController@getSchedules');
// get grade
Route::get('/student/grade', 'TSApiController@getGrades');
// add grade
Route::post('/add/grade', 'TSApiController@addGrades');
// update grade
Route::patch('/update/grade', 'TSApiController@updateGrades');

Route::post('/register', 'TSApiController@register');

Route::post('/login', 'LoginController@loginApi');
