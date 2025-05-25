<?php

namespace App\Http\Controllers;

use App\Models\TIdentitas;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\TTask;
use App\Models\TPresensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function generateReport(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $selectedUserId = $request->input('user_id');

        // Ambil user tertentu atau semua dengan role = 1
        $users = ($selectedUserId === 'all')
            ? User::where('role', 1)->get()
            : User::where('role', 1)->where('id', $selectedUserId)->get();

        $data = [];

        foreach ($users as $user) {
            $dates = CarbonPeriod::create($user->created_at, $endDate);
            $workingDays = 0;

            foreach ($dates as $date) {
                if (!$date->isWeekend()) {
                    $workingDays++;
                }
            }

            $tasks = TTask::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $task_tepat_waktu = $tasks->filter(
                fn($task) =>
                $task->status == 2 && $task->updated_at <= $task->deadline
            )->count();

            $task_terlambat = $tasks->filter(
                fn($task) =>
                $task->status == 2 && $task->updated_at > $task->deadline
            )->count();

            $task_ids = $tasks->pluck('id');
            $task_revisi = TTask::whereIn('revisi', $task_ids)->count();

            $poin_task = (($task_tepat_waktu + $task_revisi) * 1) + ($task_terlambat * -1);

            $identitasId = TIdentitas::where('user_id', $user->id)->pluck('id');
            $presensi = TPresensi::whereIn('identitas_id', $identitasId)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $hadir = $presensi->where('status', 1)->count();
            $izin = $presensi->whereIn('status', [2, 3])->count();
            $alpha = $workingDays - ($hadir + $izin);

            $poin_presensi = (($hadir + $izin) * 1) + ($alpha * -1);
            $total_poin = $poin_task + $poin_presensi;

            $data[] = [
                'nama' => $user->name,
                'jabatan' => 'Pegawai',
                'task_total' => $tasks->count(),
                'task_tepat' => $task_tepat_waktu,
                'task_telat' => $task_terlambat,
                'task_revisi' => $task_revisi,
                'hadir' => $hadir,
                'alpha' => $alpha,
                'izin' => $izin,
                'poin_task' => $poin_task,
                'poin_presensi' => $poin_presensi,
                'total_poin' => $total_poin,
            ];
        }

        $pdf = Pdf::loadView('rekap.rekap', compact('data', 'startDate', 'endDate'));
        return $pdf->download("rekap-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf");
    }




    public function form()
    {
        $users = User::where('role', 1)->get();
        return view('rekap.form', compact('users'));
    }
}
