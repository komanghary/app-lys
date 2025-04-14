<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manager List Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded-lg">

                {{-- Toast Success --}}
                @if (session('success'))
                    <div id="toast-success"
                        class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm"
                        role="alert">
                        <div
                            class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                            </svg>
                        </div>
                        <div class="ms-3 text-sm font-normal">{{ session('success') }}</div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100"
                            data-dismiss-target="#toast-success" aria-label="Close">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Tombol dan Search Form --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                    <a href="{{ route('task.manager.add') }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full sm:w-auto">
                        Tambah Task
                    </a>

                    <form method="GET" action="{{ route('task.manager.list') }}"
                        class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari task..."
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64 p-2.5">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 w-full sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- Tabel Task --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan
                                </th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tasks as $r)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $r->user->name }}
                                    </td>
                                    {{-- <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $r->created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $r->deadline }}
                                    </td> --}}
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
                                    <td class="px-6 py-4 text-sm text-gray-500 break-words">{{ $r->keterangan }}</td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('task.manager.edit', $r->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mb-2 inline-block">
                                            Edit
                                        </a>
                                        <a href="{{ route('task.preview', $r->id) }}"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                            Preview
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $tasks->appends(['search' => request('search')])->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
