    <!-- Contoh isi index.blade.php -->
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                List Task
            </h2>
        </x-slot>

        @push('scripts')
            <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
            <script src="{{ asset('js/echo.js') }}"></script> <!-- Jika menggunakan Echo standalone -->
        @endpush

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <!-- TABEL 1 -->

                <div class="bg-white p-10 shadow rounded-lg mb-4 overflow-x-auto">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 px-4">
                        <div class="mb-2 sm:mb-0">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                Task
                            </h2>
                            <p class="text-gray-800 text-sm">
                                Task dari Manager
                            </p>
                        </div>
                        <form method="GET" class="flex items-center gap-2">
                            <input type="text" name="search" placeholder="Cari keterangan..."
                                value="{{ request('search') }}" class="border px-3 py-2 rounded-md shadow text-sm w-64">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                                Cari
                            </button>
                        </form>
                    </div>
                    <table class=" divide-y divide-gray-200 w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                @php
                                    function sortLink($label, $field)
                                    {
                                        $currentSort = request('sort');
                                        $direction = request('direction', 'asc');
                                        $newDirection =
                                            $currentSort === $field && $direction === 'asc' ? 'desc' : 'asc';
                                        $arrow = $currentSort === $field ? ($direction === 'asc' ? '↑' : '↓') : '';
                                        $query = http_build_query(
                                            array_merge(request()->all(), [
                                                'sort' => $field,
                                                'direction' => $newDirection,
                                            ]),
                                        );
                                        return "<a href=\"?" .
                                            $query .
                                            "\" class=\"hover:underline\">$label $arrow</a>";
                                    }
                                @endphp
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky top-0 bg-gray-50 z-10">
                                    {!! sortLink('Waktu', 'sisa_waktu') !!}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky top-0 bg-gray-50 z-10">
                                    {!! sortLink('Deadline', 'deadline') !!}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky top-0 bg-gray-50 z-10">
                                    <label>keterangan</label>
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider sticky top-0 bg-gray-50 z-10">
                                    <div class="mt-2">
                                        <form method="GET" class="flex items-center gap-2">
                                            <select name="status" onchange="this.form.submit()"
                                                class="border px-2 py-1 rounded-md shadow text-xs w-full sm:w-48">
                                                <option value=""
                                                    {{ request('status') === null ? 'selected' : '' }}>
                                                    Semua Status</option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                    On
                                                    Progress</option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                    On
                                                    Review</option>
                                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                                    Completed</option>
                                            </select>
                                        </form>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tasks as $r)
                                <tr>
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
                                            Selesai : {{ date('d F Y - H:i', strtotime($r->completed_at)) }}
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


                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                        {{ $r->child->keterangan ?? $r->keterangan }}
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
                                                Revisi on review
                                            </a>
                                        @elseif ($r->child && $r->child->status == 0)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Preview Revisi
                                            </a>
                                        @elseif ($r->status == 0)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                On Progress
                                            </a>
                                        @elseif ($r->status == 1)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Task on review
                                            </a>
                                        @elseif ($parentStatus == 2)
                                            <a href="{{ route('task.preview', $r->id) }}"
                                                class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 inline-block">
                                                Task Completed
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-sm text-gray-500 py-6">
                                        Tidak ada task yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $tasks->appends(request()->query())->links() }}
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
