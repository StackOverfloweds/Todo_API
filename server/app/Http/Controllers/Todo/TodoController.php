<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\todoModel;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of todos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $todos = todoModel::all();
        return response()->json($todos, 200);
    }

    /**
     * Store a newly created todo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid',
            'name' => 'required|string|max:255',
            'status_task' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $todo = todoModel::create($request->all());
        return response()->json($todo, 201);
    }

    /**
     * Display the specified todo.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $todo = todoModel::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return response()->json($todo, 200);
    }

    /**
     * Update the specified todo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'status_task' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $todo = todoModel::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $todo->update($request->all());
        return response()->json($todo, 200);
    }

    /**
     * Remove the specified todo from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $todo = todoModel::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}