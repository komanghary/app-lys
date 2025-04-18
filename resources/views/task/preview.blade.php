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
                @endphp

                <p
                    class="py-4 font-semibold
                    {{ $deadline->isPast() ? 'text-red-600' : 'text-green-600' }}">
                    {{ $deadline->translatedFormat('l, d F - H:i') }}
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
                            class="inline-block text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">Lihat
                            File</a>
                    @else
                        <p class="text-gray-500">Tidak ada file yang diupload.</p>
                    @endif
                </div>
                @if (!$task->file_done)
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
                @else
                    <div class="mb-6">
                        <h3 class="text-lg font-bold">File task:</h3>
                        <a href="{{ asset('storage/' . $task->file_done) }}"
                            class="inline-block text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">
                            Lihat File
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 2 && $task->status == 1)
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
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>


</x-app-layout>
