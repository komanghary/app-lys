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

                <h3 class="text-lg font-bold mt-4">Keterangan:</h3>
                <p class="py-4">{{ $task->keterangan }}</p>
                <h3 class="text-lg font-bold mt-4">Sisa:</h3>
                @php
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    $now = \Carbon\Carbon::now();
                @endphp

                <p
                    class="font-semibold py-4
                             {{ $deadline->greaterThan($now) ? 'text-green-600' : 'text-red-600' }}">
                    {{ $deadline->diffForHumans($now, [
                        'parts' => 3,
                        'short' => true,
                        'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                    ]) }}
                </p>
                <div class="mb-6">
                    <h3 class="text-lg font-bold">File yang diupload:</h3>
                    @if ($task->file)
                        <a href="{{ asset('storage/' . $task->file) }}"
                            class="inline-block text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2 p-2 rounded-lg">Lihat
                            File</a>
                    @else
                        <p class="text-gray-500">Tidak ada file yang diupload.</p>
                    @endif
                </div>
                <form action="">
                    <div class="mb-6">
                        <p class="text-lg font-bold " for="file_input">Upload
                            Task</p>
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
            </div>
        </div>
    </div>


</x-app-layout>
