<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tasks = Task::all();
        // return response()->json($tasks);
        return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validateData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]
        );

        $task = Task::create($validateData);
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            //nincs ilyen id
            return response()->json(['message'=>"A(z) {$id} azonositoju feladat nem letezik!"], Response::HTTP_NOT_FOUND);
        }
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            //nincs ilyen id
            return response()->json(['message'=>"A(z) {$id} azonositoju feladat nem letezik!"], Response::HTTP_NOT_FOUND);
        }
        $validateData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]
        );

        $task->update($validateData);
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
