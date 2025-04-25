<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Informasi Identitas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <h1 class="text-2xl font-bold mb-4">Daftar Identitas</h1>

                <!-- Filter Pencarian -->
                <div class="mb-4">
                    <input type="text" id="searchInput" class="border p-2 w-full sm:w-1/3 rounded text-sm"
                        placeholder="Cari berdasarkan Nama, Email, Instansi, Alamat, No HP" />
                </div>

                @if ($identitas->isEmpty())
                    <p>Data identitas belum tersedia.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border text-sm sm:text-base">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                        Instansi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                        Alamat</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                        No. HP</th>
                                </tr>
                            </thead>
                            <tbody id="identitasTable">
                                @foreach ($identitas as $item)
                                    <tr class="identitas-row">
                                        <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                            {{ $item->user->name }}</td>
                                        <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                            {{ $item->user->email }}</td>
                                        <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                            {{ $item->instansi }}</td>
                                        <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                            {{ $item->alamat }}</td>
                                        <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                            {{ $item->no_hp }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $identitas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // JavaScript untuk filter pencarian langsung
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('identitasTable');
        const rows = table.getElementsByClassName('identitas-row');

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();

            Array.from(rows).forEach(row => {
                const cells = row.getElementsByTagName('td');
                let match = false;

                Array.from(cells).forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(query)) {
                        match = true;
                    }
                });

                row.style.display = match ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
