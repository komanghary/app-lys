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

                    <form method="GET" action="{{ route('task.manager.list') }}" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari task..."
                            class="border px-3 py-2 rounded-md shadow text-sm w-64">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- Tabel Task --}}
                <div class="overflow-x-auto">
                    <form method="GET" action="{{ route('task.manager.list') }}"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" onchange="this.form.submit()">
                        {{-- Filter Nama (Dropdown User) --}}
                        <select name="user_id" class="border px-3 py-2 rounded-md shadow text-sm w-full"
                            onchange="this.form.submit()">
                            <option value="">Semua Pegawai
                            </option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Status --}}
                        <select name="status" class="border px-3 py-2 rounded-md shadow text-sm w-full"
                            onchange="this.form.submit()">
                            <option value="">Semua Status
                            </option>
                            <option value="0" @selected(request('status') === '0')>Ongoing</option>
                            <option value="1" @selected(request('status') === '1')>Review</option>
                            <option value="2" @selected(request('status') === '2')>Complated</option>
                        </select>
                    </form>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap">Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap">Sisa
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap">
                                    Deadline
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap">
                                    Keterangan
                                </th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 whitespace-nowrap text-center ">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tasks as $r)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $r->user->name }}
                                    </td>

                                    {{-- <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $r->created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $r->deadline }}
                                    </td> --}}
                                    @php
                                        $deadline = $r->child
                                            ? \Carbon\Carbon::parse($r->child->deadline)
                                            : \Carbon\Carbon::parse($r->deadline);
                                        $now = \Carbon\Carbon::now();
                                    @endphp

                                    <td
                                        class="px-6 py-4 text-sm whitespace-nowrap
{{ $r->status == 2 ? 'text-gray-800' : ($deadline->greaterThan($now) ? 'text-green-600' : 'text-red-600') }}">
                                        @if ($r->status == 2)
                                            Selesai :{{ date('d F Y - H:i', strtotime($r->completed_at)) }}
                                        @else
                                            {{ $deadline->diffForHumans($now, [
                                                'parts' => 3,
                                                'short' => true,
                                                'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                            ]) }}
                                        @endif
                                    </td>

                                    @php
                                        $deadline = $r->child
                                            ? \Carbon\Carbon::parse($r->child->deadline)
                                            : \Carbon\Carbon::parse($r->deadline);
                                    @endphp

                                    <td
                                        class="px-6 py-4 text-sm whitespace-nowrap
{{ $r->status == 2 ? 'text-gray-800' : ($deadline->isPast() ? 'text-red-600' : 'text-green-600') }}">
                                        @if ($r->status == 2)
                                            {{ \Carbon\Carbon::parse($r->completed_at)->translatedFormat('l, d F - H:i') }}
                                        @else
                                            {{ $deadline->translatedFormat('l, d F - H:i') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 break-words">
                                        @if ($r->child)
                                            {{ $r->child->keterangan }}
                                        @else
                                            {{ $r->keterangan }}
                                        @endif
                                    </td>

                                    @php
                                        $isChild = $r->revisi != 0;
                                        $parentStatus = $isChild
                                            ? \App\Models\TTask::find($r->revisi)?->status
                                            : $r->status;
                                    @endphp

                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-center">
                                        {{-- Jika ini adalah TASK BARU yang telah direvisi --}}
                                        @if ($r->child && $r->child->status == 1)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Review Revisi
                                            </a>
                                        @elseif ($r->child && $r->child->status == 0)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Preview Revisi
                                            </a>
                                        @elseif ($r->status == 0)
                                            <a href="{{ route('task.manager.edit', $r->id) }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mb-2 inline-block">
                                                Edit
                                            </a>
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Preview
                                            </a>
                                        @elseif ($r->status == 1)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Review Task
                                            </a>
                                        @elseif ($parentStatus == 2)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Task Completed
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $tasks->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('filterStatus').addEventListener('change', function() {
        const selectedStatus = this.value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const statusCell = row.querySelector('.status-cell');
            const rowStatus = statusCell ? statusCell.dataset.status : '';

            if (selectedStatus === '' || rowStatus === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
