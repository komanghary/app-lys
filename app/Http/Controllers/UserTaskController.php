<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;

class UserTaskController extends Controller
{
    public function list(Request $request)
    {
        // dd($request->user()->id);
        $tasks = TTask::where("user_id", $request->user()->id)->where('status', 0)->orderBy("created_at", "desc")->paginate(10);

        return view("task.list", compact("tasks"));
    }

    public function preview($id)
    {
        // dd(auth()->guard("web")->user()->id);
        $task = TTask::where('user_id', operator: auth()->guard("web")->user()->id)->findOrFail($id);
        return view('task.preview', compact('task'));
    }
}
