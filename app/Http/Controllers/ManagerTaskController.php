<?php

namespace App\Http\Controllers;

use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;


class ManagerTaskController extends Controller
{
    public function index(Request $request)
    {
        $users = \App\Models\User::where('role', 1)->orderBy('name')->get();
        $query = TTask::with('user')->where('revisi', 0);

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
        $query->orderBy('created_at', 'desc');
        $tasks = $query->paginate(10);
        foreach ($tasks as $task) {
            $query2 = TTask::with('user')->where('revisi', operator: $task->id);
            $task->child = $query2->orderBy('created_at', 'desc')->first();
        }
        // dd($query);

        return view('task.manager.list', compact('tasks', 'users'));
    }




    public function add()
    {
        $users = User::where("role", "1")->get();
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
            "user_id" => "required|exists:users,id",
            "keterangan" => "required",
            "time" => "required",
            "day" => "required|numeric",
            "month" => "required|numeric",
            "year" => "required|numeric",
            "upload_file" => "nullable|max:1024000", // 1GB in kilobytes
        ]);

        if ($request->hasFile('upload_file')) {
            $task = TTask::create([
                "user_id" => $request->user_id,
                "keterangan" => $request->keterangan,
                "deadline" => "$request->year-$request->month-$request->day $request->time",
                "file" => $request->upload_file->store("task", "public"),
            ]);
        } else {
            $task = TTask::create([
                "user_id" => $request->user_id,
                "keterangan" => $request->keterangan,
                "deadline" => "$request->year-$request->month-$request->day $request->time",
                "file" => 0,
            ]);

        }

        // Kirim notifikasi

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

        // Cek role manager
        if (auth()->user()->role != 2) {
            abort(403, 'Unauthorized');
        }

        // Cek hanya task yang belum dikerjakan (status == 0) yang bisa dihapus
        if ($task->status != 0) {
            return redirect()->back()->with('error', 'Task hanya bisa dihapus jika belum dikerjakan.');
        }

        // Soft delete
        $task->update(['deleted_at' => now()]);

        return redirect()->route('task.manager.list')->with('success', 'Berhasil menghapus task.');
    }
    public function markAsCompleted($id)
    {
        $task = TTask::findOrFail($id);

        // Cek hanya Manager (role 2) dan task status harus On Review (1)
        if (auth()->user()->role == 2 && $task->status == 1) {
            $now = now();

            // Ambil ID parent (jika ini child)
            $parentId = $task->revisi != 0 ? $task->revisi : $task->id;

            // Update parent
            TTask::where('id', $parentId)->update([
                'status' => 2,
                'completed_at' => $now
            ]);

            // Update semua child yang revisi == parentId
            TTask::where('revisi', $parentId)->update([
                'status' => 2,
                'completed_at' => $now
            ]);

            return redirect()->route('task.manager.list')->with('success', 'Task berhasil diselesaikan.');
        }

        return redirect()->route('task.manager.list')->with('error', 'Gagal menyelesaikan task.');
    }
    public function revisi($id)
    {
        $task = TTask::findOrFail($id);

        // Ambil ID parent jika ini adalah child
        $parentId = ($task->revisi && $task->revisi != 0) ? $task->revisi : $task->id;

        $newTask = TTask::create([
            'user_id' => $task->user_id,
            'deadline' => $task->deadline,
            'status' => 0,
            'keterangan' => $task->keterangan,
            'revisi' => $parentId, // sekarang pasti benar
            // kolom lain yang diperlukan...
        ]);

        return redirect()->route('task.manager.edit', $newTask->id);
    }

}
