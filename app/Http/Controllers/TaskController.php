<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    // hàm lọc danh sách task
    public function index(Request $request){
        $user = $request->user();
        $view = $request->query('view', 'today');

        $query = Task::where('user_id', $user->id);

        if($view === 'today'){
            $query->whereDate('due_date', Carbon::today());
        }
        elseif ($view = 'upcoming'){
            $query->where('due_date', '>', Carbon::now());
        }
        elseif($view = 'overdue'){
            $query->where('due_date', '<', Carbon::now())->where('status', 'todo');
        }

        $tasks = $query->orderBy('due_date', 'asc')->get();
        return response()->json($tasks);
    }

    //hàm lưu công việc mới
    public function store(Request $request){
        $user = $request->user();

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' =>$request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => 'todo',
            'category' => $request->category
        ]);

        return response()->json($task, 201);
    }

    // hàm update
    public function update(Request $request, $id){
        $user = $request->user();

        $task = Task::where('id', $id)->where('user_id', $user->id)->FirstOrFail();

        $task->update($request->only([
            'title',
            'description',
            'due_date',
            'priority',
            'status',
            'category'
        ]));

        return response()->json($task);
    }

    public function destroy(Request $request, $id){
        $user = $request->user();

        $task = Task::where("id", $id)->where("user_id", $user->id)->FirstOrFail();

        $task->delele();
        return response()->json(['message'=>'Deleted']);
    }
}
