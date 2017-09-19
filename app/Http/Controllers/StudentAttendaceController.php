<?php

namespace App\Http\Controllers;

use App\StudentAttendance;
use Illuminate\Http\Request;

class StudentAttendaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentAttendance = StudentAttendance::all();
        return response()->json($studentAttendance);
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

            $student_attendance = $request->all();
            StudentAttendance::create($student_attendance);
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
     * @param  \App\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAttendance $studentAttendance)
    {
        return response()->json($studentAttendance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAttendance $studentAttendance)
    {
        try {
            \DB::beginTransaction();
            $studentAttendance->update($request->all());
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
     * @param  \App\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAttendance $studentAttendance)
    {
        try {

            \DB::beginTransaction();
            $studentAttendance->delete();
            \DB::commit();
            $msg = 'Delete Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }

        return response()->json(compact('msg', 'error_message'));
    }
}
