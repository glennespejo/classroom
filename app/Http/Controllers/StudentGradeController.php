<?php

namespace App\Http\Controllers;

use App\StudentGrade;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentGrades = StudentGrade::all();
        return response()->json($studentGrades);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            \DB::beginTransaction();

            $student_grade = $request->all();
            StudentGrade::create($student_grade);
            \DB::commit();
            $msg = 'Add Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }

        return response()->json(compact('msg', 'error_message'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function show(StudentGrade $studentGrade)
    {
        return response()->json($studentGrade);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentGrade $studentGrade)
    {
        try {
            \DB::beginTransaction();
            $studentGrade->update($request->all());
            \DB::commit();
            $msg = 'Update Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }
        return response()->json(compact('msg', 'error_message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentGrade $studentGrade)
    {
        try {

            \DB::beginTransaction();
            $studentGrade->delete();
            \DB::commit();
            $msg = 'Delete Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }

        return response()->json(compact('msg', 'error_message'));
    }
}
