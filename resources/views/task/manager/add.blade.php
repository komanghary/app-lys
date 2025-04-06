<!-- Contoh isi index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded-lg">
                <form action="{{ route('task.manager.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div class="max-w-sm mx-auto">
                            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">Nama pegawai
                                magang</label>
                            <select id="countries" name="user_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.50">
                                <option selected>Nama pegawai magang</option>
                                @foreach ($users as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                                    <input type="time" id="end-time" name="time"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
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
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
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
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
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
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="text" class="block mb-2 text-sm font-medium text-gray-900 ">Keterangan</label>
                        <textarea type="text" id="email" name="keterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Keterangan" required> </textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 " for="file_input">Upload
                            file</label>
                        <input name="upload_file"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            id="file_input" type="file">
                    </div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
