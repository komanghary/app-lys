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
                <table class="min-w-full divide-y divide-gray-200 w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            @php
                                function sortLink($label, $field)
                                {
                                    $currentSort = request('sort');
                                    $direction = request('direction', 'asc');
                                    $newDirection = $currentSort === $field && $direction === 'asc' ? 'desc' : 'asc';
                                    $arrow = $currentSort === $field ? ($direction === 'asc' ? '↑' : '↓') : '';
                                    $query = http_build_query(
                                        array_merge(request()->all(), ['sort' => $field, 'direction' => $newDirection]),
                                    );
                                    return "<a href=\"?" . $query . "\" class=\"hover:underline\">$label $arrow</a>";
                                }
                            @endphp

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {!! sortLink('Waktu', 'sisa_waktu') !!}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {!! sortLink('Deadline', 'deadline') !!}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <label>keterangan</label>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
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
                                <td class="px-6 py-4 text-center align-middle">
                                    @if ($r->status == 1)
                                        <a href="{{ route('task.preview', $r->id) }}"
                                            class="inline-block
                                            bg-yellow-400 text-white text-xs hover:bg-yellow-500 focus:ring-4 font-semibold px-3 py-1 rounded-lg
                                            shadow-sm">
                                            On Review
                                        </a>
                                    @else
                                        <a href="{{ route('task.preview', $r->id) }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                                            Preview
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
