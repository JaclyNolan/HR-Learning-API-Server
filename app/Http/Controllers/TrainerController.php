<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function profileForTrainer(Request $request)
    {
        $user = $request->user()->load(['trainer']);

        $trainer = $user->trainer;
        $trainer->makeHidden('created_at', 'updated_at', 'deleted_at');
        return response()->json($trainer, 200);
    }

    public function updateProfileForTrainer(Request $request)
    {
        $user = $request->user()->load(['trainer']);

        $trainer = $user->trainer;
        $trainer->name = $request->input('name');
        $trainer->type = $request->input('type');
        $trainer->education = $request->input('education');
        $trainer->working_place = $request->input('working_place');
        $trainer->phone_number = $request->input('phone_number');
        $trainer->email = $request->input('email');
        $trainer->save();

        return response()->json($trainer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
