<?php

namespace App\Http\Controllers\API\Todo;

use App\Http\Controllers\Controller;
use App\SubTodo;
use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class TodoController extends Controller
{
    public function index(){
        try {
            $data = $this->fetchTodo();
            return response(['todos'=>$data, 'status' => true], 200);
        }
        catch(\Exception $exception){
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }

    public function store(Request $request, Todo $todo)
    {
        try {
            $validator =  Validator::make($request->all(),[
                'title' => 'bail|required|min:3|max:50|unique:todos'
            ]);

            if ($validator->fails()){
                $data = [
                    'error' =>"Title Field is Required",
                ];
                return response($data,422);
            }

            // add new task if validation passed
            $newTodo = $todo->createTodo($request);

            if ($request->subTodo){
                foreach ($request->subTodo as $subTask){
                    $subTodo = new SubTodo();
                    $subTodo->todo_id = $newTodo->id;
                    $subTodo->todo = $subTask['value'];
                    $subTodo->token = Str::random(15);
                    $subTodo->save();
                }
            }
            //fetch todos
            $todos = $this->fetchTodo();

            //return result
            return response(['todo'=>$todos, 'status' => true, "message" =>"Todo Successfully Created"], 200);
        }
        catch (\Exception $exception){
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $todo = Todo::where(['user_id' => Auth::user()->id, 'id' => $id])->first();
            if (!$todo){
                return response(['error' => 'Todo does not exist'] , 404);
            }
            if($todo->delete()){
                $todos = $this->fetchTodo();
                return response(['todo'=>$todos, 'status' => true, 'message' => "Todo Successfully deleted"], 200);
            }
            return response(['error' => 'Todo could not be deleted', 'status' => false] , 400);
        }
        catch(\Exception $exception){
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }

    public function update(Request $request,$id, SubTodo $subTodoInstance){
        try {
            $validator =  Validator::make($request->all(),[
                'isCompleted' => 'bail|required',
/*                'todoType' => 'bail|required'*/
            ]);

            if ($validator->fails()){
                $data = [
                    'errors' =>"Kindly Ensure all Fields are Correctly Filled",
                ];
                return response($data,422);
            }
            /*switch ($request->todoType){
                case "main":
                    $todo = Todo::where(['user_id' => Auth::user()->id, 'id' => $id])->first();
                    if (!$todo){
                        return response(['error' => 'Todo does not exist'] , 401);
                    }
                    if ($request->isCompleted === 0 ){
                        $todo->isCompleted = $request->isCompleted;
                        $todo->save();
                        $todos = $this->fetchTodo();
                        return response(['todo'=>$todos, 'success' => true], 200);
                    }
                    else{
                        $status = $this->confirmTodo($todo);
                        if ($status){
                            $todo->isCompleted = $request->isCompleted;
                            $todo->save();
                            $todos = $this->fetchTodo();
                            return response(['todo'=>$todos, 'success' => true], 200);
                        }
                        else{
                            return response(
                                ["message" => "There are Pending todos, kindly finish them up",
                                'status' => false],
                          422);
                        }
                    }
                    break;
                case "subTodo":*/
            $todo = $subTodoInstance->fetchUserTodo($id);
            if (!$todo){
                return response(['error' => 'Todo does not exist'] , 404);
            }
            $todo->isCompleted = $request->isCompleted;
            $todo->save();
            $todos = $this->fetchTodo();
            return response(['todo'=>$todos, 'status' => true], 200);
            /*        break;

                default:
                    break;
            }*/
        }
        catch (\Exception $exception){
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }

    public function fetchTodo (){
        $todos = Todo::where('user_id', Auth::user()->id)->get();
        $data = [];
        foreach ($todos as $todo){
            $sub_todos = [];
            foreach ($todo->subTodos as $event){
                array_push($sub_todos,$event);
            }
            $main = [
                "main" => $todo
            ];
            array_push($data, $main);
        }
        return $data;
    }

    public function confirmTodo($todo){
        $status = true;
        foreach ($todo->subTodos as $sub){
           if ($sub->isCompleted === 0) {
               $status = false;
               break;
           }
        }
        return $status;
    }
}
