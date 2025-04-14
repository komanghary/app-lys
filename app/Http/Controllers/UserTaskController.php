<?php

namespace App\Http\Controllers;
use App\Models\TTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class UserTaskController extends Controller
{
    public function list(Request $request)
    {
        $query = TTask::where("user_id", $request->user()->id);

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        // Sortable fields
        $sortableFields = ['created_at', 'deadline', 'keterangan', 'sisa_waktu'];
        $sort = $request->get('sort');
        $direction = $request->get('direction', 'asc');

        if (in_array($sort, $sortableFields)) {
            if ($sort === 'sisa_waktu') {
                // Sisa waktu pendek = deadline terdekat, jadi tetap urutkan berdasarkan deadline
                $query->orderBy('deadline', $direction);
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tasks = $query->paginate(10)->withQueryString();

        return view("task.list", compact("tasks"));
    }

    public function preview($id)
    {
        $task = TTask::findOrFail($id);

        // Cek apakah user punya akses
        if (auth()->user()->role == 1 && $task->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke task ini.');
        }

        return view('task.preview', compact('task'));
    }
    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'upload_file' => 'required|file|max:1024000',
        ]);

        $task = TTask::findOrFail($id);

        // Upload ke folder khusus task
        $path = $request->file('upload_file')->store("task_done_files/task_{$task->id}", 'public');

        // Simpan path ke database
        $task->file_done = $path;
        $task->status = 1; // true
        $task->updated_at = Carbon::now(); // Override waktu update ke waktu submit file

        $task->save();

        // return back()->with('success', 'File berhasil diupload.');
        return redirect()->route('task.list')->with('success', 'File berhasil diupload.');

    }


}