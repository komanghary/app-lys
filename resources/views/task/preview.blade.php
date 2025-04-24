<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            List Task
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-lg font-bold">Deadline:</h3>
                @php
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    $updated = \Carbon\Carbon::parse($task->updated_at);

                    $deadlineClass = 'text-sm italic'; // default

                    if ($task->status != 0) {
                        $deadlineClass = $updated->lessThanOrEqualTo($deadline) ? 'text-green-600' : 'text-red-600';
                    }
                @endphp

                <p class="text-sm font-medium {{ $deadlineClass }}">
                    {{ $deadline->translatedFormat('l, d F Y - H:i') }}
                </p>
                <h3 class="text-lg font-bold mt-4">Status Submit:</h3>
                @php
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    $updated = \Carbon\Carbon::parse($task->updated_at);
                @endphp

                @if ($task->status == 1)
                    @if ($updated->greaterThan($deadline))
                        <p class="font-semibold py-4 text-red-600">
                            Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }} <br>
                            <span class="text-sm italic">Telat
                                {{ $deadline->diffForHumans($updated, [
                                    'parts' => 2,
                                    'short' => true,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                ]) }}</span>
                        </p>
                    @else
                        <p class="font-semibold py-4 text-green-600">
                            Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }}
                        </p>
                    @endif
                @elseif ($task->status == 2)
                    @if ($updated->greaterThan($deadline))
                        <p class="font-semibold py-4 text-red-600">
                            Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }} <br>
                            <span class="text-sm italic">Telat
                                {{ $deadline->diffForHumans($updated, [
                                    'parts' => 2,
                                    'short' => true,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                ]) }}</span>
                        </p>
                    @else
                        <p class="font-semibold py-4 text-green-600">
                            Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }}
                        </p>
                    @endif
                @else
                    @php
                        $now = \Carbon\Carbon::now();
                    @endphp
                    <p
                        class="font-semibold py-4 {{ $deadline->greaterThan($now) ? 'text-green-600' : 'text-red-600' }}">
                        {{ $deadline->diffForHumans($now, [
                            'parts' => 3,
                            'short' => true,
                            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                        ]) }}
                    </p>
                @endif

                <h3 class="text-lg font-bold mt-4">Keterangan:</h3>
                <p class="py-4">{{ $task->keterangan }}</p>
                <div class="mb-6">
                    <h3 class="text-lg font-bold">File:</h3>
                    @if ($task->file)
                        <a href="{{ asset('storage/' . $task->file) }}"
                            class="inline-block text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">Lihat
                            File</a>
                    @else
                        <p class="text-gray-500">Tidak ada file yang diupload.</p>
                    @endif
                </div>
                @if (!$task->file_done && Auth::user()->role == 1)
                    <form action="{{ route('task.upload', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <p class="text-lg font-bold" for="file_input">Upload Task</p>
                            <input name="upload_file"
                                class="block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none px-3 py-3"
                                id="file_input" type="file">
                            @error('upload_file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex">
                            @if (Auth::user()->role != 2)
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Submit
                                </button>
                            @endif
                        </div>
                    </form>
                @elseif ($task->file_done)
                    <div class="mb-6">
                        <h3 class="text-lg font-bold">File task:</h3>
                        <a href="{{ asset('storage/' . $task->file_done) }}"
                            class="inline-block text-white bg-green-600 = hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">
                            Lihat File
                        </a>
                    </div>
                @endif

                @php
                    $hasRevisions = \App\Models\TTask::where('revisi', $task->id)->exists();
                @endphp

                @if (Auth::user()->role == 2 && $task->status == 1 && !$hasRevisions)
                    <div class="flex gap-2 mt-2">
                        <form action="{{ route('task.revisi', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Revisi
                            </button>
                        </form>
                        <form action="{{ route('task.complete', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Complete
                            </button>
                        </form>
                    </div>
                @elseif (Auth::user()->role == 2 && $task->status == 0)
                    <div class="flex gap-2 mt-2">
                        <form action="{{ route('task.manager.delete', $task->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @if ($revisions->count() > 0)
                <h2 class="text-xl font-bold mt-10 mb-4">Revisi</h2>
                @foreach ($revisions as $revisi)
                    <div class="bg-white p-6 shadow rounded-lg mb-6">
                        <h3 class="text-lg font-bold">Deadline:</h3>
                        @php
                            // Ambil task yang akan dicek: kalau ada child, pakai child-nya
                            $activeTask = $task->child ?? $task;

                            $deadline = \Carbon\Carbon::parse($revisi->deadline);
                            $updated = \Carbon\Carbon::parse($revisi->updated_at);

                            $deadlineClass = 'text-gray-800'; // default

                            if ($activeTask->status != 0) {
                                $deadlineClass = $updated->lessThanOrEqualTo($deadline)
                                    ? 'text-green-600'
                                    : 'text-red-600';
                            }
                        @endphp

                        <p class="text-sm font-medium {{ $deadlineClass }}">
                            {{ $deadline->translatedFormat('l, d F Y - H:i') }}
                        </p>

                        <h3 class="text-lg font-bold mt-4">Status Submit:</h3>
                        @php
                            $updated = \Carbon\Carbon::parse($revisi->updated_at);
                        @endphp

                        @if ($revisi->status == 1)
                            @if ($updated->greaterThan($deadline))
                                <p class="font-semibold py-4 text-red-600">
                                    Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }} <br>
                                    <span class="text-sm italic">Telat
                                        {{ $deadline->diffForHumans($updated, [
                                            'parts' => 2,
                                            'short' => true,
                                            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                        ]) }}</span>
                                </p>
                            @else
                                <p class="font-semibold py-4 text-green-600">
                                    Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }}
                                </p>
                            @endif
                        @elseif ($task->status == 2)
                            @if ($updated->greaterThan($deadline))
                                <p class="font-semibold py-4 text-red-600">
                                    Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }} <br>
                                    <span class="text-sm italic">Telat
                                        {{ $deadline->diffForHumans($updated, [
                                            'parts' => 2,
                                            'short' => true,
                                            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                        ]) }}</span>
                                </p>
                            @else
                                <p class="font-semibold py-4 text-green-600">
                                    Dikirim: {{ $updated->translatedFormat('l, d F - H:i') }}
                                </p>
                            @endif
                        @else
                            @php
                                $now = \Carbon\Carbon::now();
                            @endphp
                            <p
                                class="font-semibold py-4 {{ $deadline->greaterThan($now) ? 'text-green-600' : 'text-red-600' }}">
                                {{ $deadline->diffForHumans($now, [
                                    'parts' => 3,
                                    'short' => true,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                ]) }}
                            </p>
                        @endif

                        <h3 class="text-lg font-bold mt-4">Keterangan:</h3>
                        <p class="py-4">{{ $revisi->keterangan }}</p>

                        <div class="mb-6">
                            <h3 class="text-lg font-bold">File:</h3>
                            @if ($revisi->file)
                                <a href="{{ asset('storage/' . $revisi->file) }}"
                                    class="inline-block text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">
                                    Lihat File
                                </a>
                            @else
                                <p class="text-gray-500">Tidak ada file yang diupload.</p>
                            @endif
                        </div>

                        @php
                            // Ambil task revisi terakhir (jika ada)
                            $lastRevisi = $revisi;
                            while (true) {
                                $next = \App\Models\TTask::where('revisi', $lastRevisi->id)->first();
                                if (!$next) {
                                    break;
                                }
                                $lastRevisi = $next;
                            }
                            $uploadTask = !$lastRevisi->file_done ? $lastRevisi : null;
                            $activeTask = $lastRevisi;
                        @endphp

                        @if ($uploadTask && Auth::user()->role != 2)
                            <form action="{{ route('task.upload', $uploadTask->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-6">
                                    <p class="text-lg font-bold" for="file_input">Upload Task</p>
                                    <input name="upload_file"
                                        class="block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none px-3 py-3"
                                        id="file_input" type="file">
                                    @error('upload_file')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        @elseif ($lastRevisi->file_done)
                            <div class="mb-6">
                                <h3 class="text-lg font-bold">File task:</h3>
                                <a href="{{ asset('storage/' . $lastRevisi->file_done) }}"
                                    class="inline-block text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">
                                    Lihat File
                                </a>
                            </div>
                        @endif
                        @if (Auth::user()->role == 2 &&
                                $activeTask->status == 1 &&
                                !\App\Models\TTask::where('revisi', $activeTask->id)->exists())
                            <div class="flex gap-2 mt-2">
                                <form action="{{ route('task.revisi', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Revisi
                                    </button>
                                </form>
                                <form action="{{ route('task.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        Complete
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                @endforeach
            @endif

        </div>
    </div>


</x-app-layout>
