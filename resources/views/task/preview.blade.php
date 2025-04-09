<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Preview Task</h2>
    </x-slot>

    <div class="p-6">
        <h3 class="text-lg font-bold">Deadline:</h3>
        <p>{{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('l, d F Y - H:i') }}</p>

        <h3 class="text-lg font-bold mt-4">Keterangan:</h3>
        <p>{{ $task->keterangan }}</p>
    </div>
</x-app-layout>
