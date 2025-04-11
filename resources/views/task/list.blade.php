<!-- Contoh isi index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            List Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- TABEL 1 -->
            <div class="bg-white p-6 shadow rounded-lg mb-4 overflow-x-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight m-4">
                    Task
                </h2>
                <p class=" text-gray-800 leading-tight m-4 text-sm">
                    Task dari Manager
                </p>
                <table class="min-w-full divide-y divide-gray-200 w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium w-10 text-gray-500 uppercase tracking-wider">
                                Waktu</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium w-10 text-gray-500 uppercase tracking-wider">
                                Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tasks as $r)
                            <tr>
                                @php
                                    $deadline = \Carbon\Carbon::parse($r->deadline);
                                    $now = \Carbon\Carbon::now();
                                @endphp

                                <td
                                    class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap
                                {{ $deadline->greaterThan($now) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $deadline->diffForHumans($now, [
                                        'parts' => 3,
                                        'short' => true,
                                        'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                    ]) }}
                                </td>

                                @php
                                    $deadline = \Carbon\Carbon::parse($r->deadline);
                                @endphp

                                <td
                                    class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap
                                    {{ $deadline->isPast() ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $deadline->translatedFormat('l, d F - H:i') }}
                                </td>


                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">{{ $r->keterangan }}</td>
                                <td class="px-6 py-4 text-center align-middle ">
                                    <a href="{{ route('tasks.preview', $r->id) }}"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                                        Preview
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
