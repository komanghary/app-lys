<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log Aktivitas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <div class="container">
                    <h1 class="text-2xl font-bold mb-4">Daftar Log Aktivitas</h1>

                    @if ($logs->isEmpty())
                        <p>Data log aktivitas belum tersedia.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border text-sm sm:text-base">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            User</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Aktivitas</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $log->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $log->user->name ?? 'System' }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $log->action }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $log->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
