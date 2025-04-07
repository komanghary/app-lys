<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;

class UserTaskController extends Controller
{
    public function list(Request $request)
    {
        $tasks = TTask::where("user_id", $request->user()->id)->orderBy("created_at", "desc")->paginate(10);
        return view("task.list", compact("tasks"));
    }
}
