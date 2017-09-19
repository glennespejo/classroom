<?php

namespace App\Http\Controllers;

use App\SubjectSchedule;
use Illuminate\Http\Request;

class SubjectScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjectSchedules = SubjectSchedule::all();
        return response()->json($subjectSchedule);
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

            $subject_schedule = $request->all();
            SubjectSchedule::create($subject_schedule);
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
     * @param  \App\SubjectSchedule  $subjectSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectSchedule $subjectSchedule)
    {
        return response()->json($subjectSchedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubjectSchedule  $subjectSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(SubjectSchedule $subjectSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubjectSchedule  $subjectSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectSchedule $subjectSchedule)
    {
        try {
            \DB::beginTransaction();
            $subjectSchedule->update($request->all());
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
     * @param  \App\SubjectSchedule  $subjectSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectSchedule $subjectSchedule)
    {
        try {

            \DB::beginTransaction();
            $subjectSchedule->delete();
            \DB::commit();
            $msg = 'Delete Success!';
        } catch (\Exception $e) {
            \DB::rollBack();
            $error_message = $e->getMessage();
        }

        return response()->json(compact('msg', 'error_message'));
    }
}
