<?php

namespace App\Http\Controllers;

use App\StudentSubject;
use Illuminate\Http\Request;

class StudentSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentSubjects = StudentSubject::all();
        return response()->json($studentSubjects);
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

            $student_subject = $request->all();
            StudentSubject::create($student_subject);
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
     * @param  \App\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function show(StudentSubject $studentSubject)
    {
        return response()->json($studentSubject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentSubject $studentSubject)
    {
        try {
            \DB::beginTransaction();
            $studentSubject->update($request->all());
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
     * @param  \App\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentSubject $studentSubject)
    {
        try {

            \DB::beginTransaction();
            $studentSubject->delete();
            \DB::commit();
            $msg = 'Delete Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }

        return response()->json(compact('msg', 'error_message'));
    }
}
