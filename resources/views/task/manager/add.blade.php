<!-- Contoh isi index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $task->id ? 'Edit' : 'Tambah' }} Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded-lg">
                <form action="{{ $task?->id ? route('task.manager.update', $task?->id) : route('task.manager.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label for="nama_pegawai" class="block mb-2 text-sm font-medium text-gray-900 ">Nama pegawai
                            magang</label>
                        <select id="nama_pegawai" name="user_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.50">
                            <option selected>Nama pegawai magang</option>
                            @foreach ($users as $r)
                                <option value="{{ $r->id }}" @selected(old('user_id', $task?->user_id) == $r->id)>{{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <div class="max-w-[16rem] grid grid-cols-2 gap-4">
                            <div>
                                <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @php
                                        $time = $task && $task->deadline ? date('H:i', strtotime($task->deadline)) : '';
                                    @endphp
                                    <input type="time" id="end-time" name="time" value="{{ old('time', $time) }}"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm
                                    rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" required />
                                </div>
                                @error('time')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <!-- Tanggal -->
                        <div>
                            <label for="day" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <select id="day" name="day"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" @selected(old('day', $task?->day) == $r->id)>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('day')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bulan -->
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                            <select id="month" name="month"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @php
                                    $months = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                    ];
                                @endphp
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}" @selected(old('month', $task?->month) == $r->id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('month')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <select id="year" name="year"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @php
                                    $year = date('Y');
                                @endphp
                                @for ($i = $year; $i <= $year + 10; $i++)
                                    <option value="{{ $i }}" @selected(old('year', $task?->year) == $r->id)>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                            @error('year')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900 ">Keterangan</label>
                        <textarea type="keterangan" disabled id="keterangan" name="keterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Keterangan">{{ old('keterangan', $task?->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 " for="file_input">Upload
                            file</label>
                        <input name="upload_file"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            id="file_input" type="file">
                        @error('upload_file')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @if ($task?->file)
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">File yang diupload:</label>
                            <a href="{{ asset('storage/' . $task->file) }}"
                                class="inline-block px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Lihat
                                File</a>
                            </a>
                        </div>
                    @endif
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        id="btnedit">Edit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    var btnedit = document.getElementById('btnedit');
    btnedit.addEventListener('click', function() {
        console.log('clicked');
        var keterangan = document.getElementById('keterangan');
        keterangan.removeAttribute('disabled');
        btnedit.textContent = 'Simpan';
    });
</script>
