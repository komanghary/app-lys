<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6 bg-white shadow-md rounded">
        <h2 class="text-xl font-bold mb-4">Log Aktivitas</h2>

        <table class="w-full table-auto border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Aktivitas</th>
                    <th class="px-4 py-2 border">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="border px-4 py-2">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="border px-4 py-2">{{ $log->user->name ?? 'System' }}</td>
                        <td class="border px-4 py-2">{{ $log->action }}</td>
                        <td class="border px-4 py-2">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada log ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
