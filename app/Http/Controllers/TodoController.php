<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Validator;

class TodoController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(){
        $todos = Todo::all();
        return response()->json([
            'status' => 'success',
            'todos' => $todos,
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required|unique:todos,title|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->messages()], 401);
        }
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully',
            'todo' => $todo,
        ]);
    }

    public function show($id){
        $todo = Todo::find($id);
        return response()->json([
            'status' => 'success',
            'todo' => $todo,
        ]);
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->messages()], 401);
        }

        $todo = Todo::find($id);
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'todo' => $todo,
        ]);
    }
    public function destroy($id){
        $todo = Todo::find($id);
        $todo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo deleted successfully',
            'todo' => $todo,
        ]);
    }
}
