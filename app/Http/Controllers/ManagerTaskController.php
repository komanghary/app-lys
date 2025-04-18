<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerTaskController extends Controller
{
    public function index(Request $request)
    {
        $users = \App\Models\User::orderBy('name')->get(); // ambil untuk dropdown

        $query = TTask::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('keterangan', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $tasks = $query->paginate(10);

        return view('task.manager.list', compact('tasks', 'users'));
    }




    public function add()
    {
        $users = User::where("role", "1")->get();
        $task = new TTask();
        $task = new TTask();
        $task->day = date('j');
        $task->month = date('n');
        $task->year = date('Y');
        return view("task.manager.add", compact("users", "task"));
    }

    public function store(Request $request)
    {
        $deadline = "$request->year-$request->month-$request->day $request->time";

        if (strtotime($deadline) <= time()) {
            return back()->withErrors(['deadline' => 'Tanggal dan waktu deadline harus di masa depan.'])->withInput();
        }

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
            "file" => $request->upload_file->store("task", "public"),
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
            "upload_file" => "max:1024000", // 1GB in kilobytes
        ]);

        $task = TTask::findOrFail($id);

        $task->user_id = $request->user_id;
        $task->keterangan = $request->keterangan;
        $task->deadline = "$request->year-$request->month-$request->day $request->time";
        if ($request->upload_file) {
            $task->file = $request->upload_file->store("task", "public");
        }

        $task->save();

        return redirect()->route("task.manager.list")->with("success", "Berhasil mengupdate task");
    }

    public function delete($id)
    {
        $task = TTask::findOrFail($id);
        $task->delete();
        return redirect()->route("task.manager.list")->with("success", "Berhasil menghapus task");
    }
    public function markAsCompleted($id)
    {
        $task = TTask::findOrFail($id);

        // Cek hanya Manager yang boleh (role 2) dan task sedang On Review (status 1)
        if (auth()->user()->role == 2 && $task->status == 1) {
            $task->status = 2; // set ke Completed
            $task->save();

            return redirect()->route('task-manager')->with('success', 'Task berhasil diselesaikan.');
        }

        return redirect()->route('task-manager')->with('success', 'Task berhasil diselesaikan.');
    }
    public function revisi($id)
    {
        $taskLama = TTask::findOrFail($id);

        $taskBaru = TTask::create([
            'user_id' => $taskLama->user_id,
            'deadline' => $taskLama->deadline,
            'keterangan' => $taskLama->keterangan,
            'revisi' => $taskLama->id,
            'status' => 0,
        ]);

        return redirect()->route('task.manager.edit', $taskBaru->id);
    }
}
