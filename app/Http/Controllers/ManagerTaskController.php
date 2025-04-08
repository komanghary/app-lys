<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerTaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = TTask::orderBy("created_at", "desc")->paginate(10);
        return view("task.manager.list", compact("tasks"));
    }

    public function add()
    {
        $users = User::where("role", "1")->get();
        $task = new TTask();
        return view("task.manager.add", compact("users", "task"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "keterangan" => "required",
            "time" => "required",
            "day" => "required|numeric",
            "month" => "required|numeric",
            "year" => "required|numeric",
            "upload_file" => "required|max:2048",
        ]);

        TTask::create([
            "user_id" => $request->user_id,
            "keterangan" => $request->keterangan,
            "deadline" => "$request->year-$request->month-$request->day $request->time",
            "file" => $request->upload_file->store("task"),
        ]);

        return redirect()->route("task.manager.list")->with("success", "Berhasil menambahkan task");
    }

    public function edit($id)
    {
        $task = TTask::findOrFail($id);
        $users = User::where("role", "1")->get();
        return view("task.manager.add", compact("task", "users"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "keterangan" => "required",
            "time" => "required",
            "day" => "required|numeric",
            "month" => "required|numeric",
            "year" => "required|numeric",
            "upload_file" => "max:2048",
        ]);

        $task = TTask::findOrFail($id);

        $task->update([
            "user_id" => $request->user_id,
            "keterangan" => $request->keterangan,
            "deadline" => "$request->year-$request->month-$request->day $request->time",
            "file" => $request->upload_file && $request->upload_file->store("task"),
        ]);

        return redirect()->route("task.manager.list")->with("success", "Berhasil mengupdate task");
    }

    public function delete($id)
    {
        $task = TTask::findOrFail($id);
        $task->delete();
        return redirect()->route("task.manager.list")->with("success", "Berhasil menghapus task");
    }
}
