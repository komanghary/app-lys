<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TTask;

class TaskCalendarController extends Controller
{
    public function index()
    {
        $tasks = TTask::whereNull('revisi')->get();

        $events = $tasks->map(function($task) {
            $date = $task->deadline ?? $task->created_at;
            $dateString = $date ? $date->format('Y-m-d') : null;

            switch ($task->status) {
                case 1:
                    $color = '#ffc107'; // belum dikerjakan
                    break;
                case 2:
                    $color = '#007bff'; // sedang dikerjakan
                    break;
                case 3:
                    $color = '#28a745'; // selesai
                    break;
                default:
                    $color = '#6c757d';
                    break;
            }

            return [
                'id' => $task->id,
                'title' => $task->keterangan ?? 'Tanpa Keterangan',
                'start' => $dateString,
                'allDay' => true,
                'color' => $color,
                'url' => route('task.preview', $task->id),
            ];
        });

        return view('task-calendar', compact('events'));
    }
}
