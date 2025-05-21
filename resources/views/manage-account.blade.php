<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Management Akun
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 text-green-600 font-semibold bg-green-100 border border-green-400 rounded p-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-10">
                    <h1 class="text-2xl font-bold mb-4">Verifikasi Akun</h1>
                    @if ($pendingUsers->count() === 0)
                        <p>Tidak ada Verifikasi Akun.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border text-sm sm:text-base">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Nama Panjang</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-normal break-words max-w-md border">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Instansi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingUsers as $user)
                                        <tr>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->name }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->identitas->full_name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-md border">
                                                {{ $user->email }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->identitas->instansi ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-nowrap border text-center">
                                                <div class="flex space-x-2 justify-center">
                                                    <form action="{{ route('manage.account.verify', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button
                                                            class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                            Verifikasi
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('manage.account.cancel', $user->id) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                            Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <h1 class="text-2xl font-bold mb-4 mt-6">Akun Terdaftar</h1>
                    @if ($users->isEmpty())
                        <p>Data akun belum tersedia.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border text-sm sm:text-base">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-normal break-words max-w-xs border">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Role</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->name }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-md border">
                                                {{ $user->email }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                @if ($user->role == 1)
                                                    Pegawai
                                                @elseif ($user->role == 2)
                                                    Manager
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm whitespace-nowrap border text-center">
                                                <form action="{{ route('manage.account.update', $user->id) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="role" class="border rounded px-7 py-1 text-sm">
                                                        <option value="1"
                                                            {{ $user->role == 1 ? 'selected' : '' }}>
                                                            Pegawai</option>
                                                        <option value="2"
                                                            {{ $user->role == 2 ? 'selected' : '' }}>
                                                            Manager</option>
                                                    </select>
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Simpan</button>
                                                </form>
                                                @if (is_null($user->deleted_at))
                                                    <form action="{{ route('manage.account.destroy', $user->id) }}"
                                                        method="POST" class="inline-block"
                                                        onsubmit="return confirm('Hapus akun ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="bg-red-500 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('manage.account.restore', $user->id) }}"
                                                        method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-green-600 text-white px-3 py-1 rounded text-sm">Pulihkan</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold mb-4 mt-6">Akun Terhapus</h1>
                    @if ($deletedUsers->isEmpty())
                        <p>Data akun terhapus belum tersedia.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border text-sm sm:text-base">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-normal break-words max-w-md border">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Role</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 whitespace-nowrap border">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deletedUsers as $user)
                                        <tr>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->name }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-md border">
                                                {{ $user->email }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-normal break-words max-w-xs border">
                                                {{ $user->role == 1 ? 'Pegawai' : 'Manager' }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-nowrap border text-center">
                                                <form action="{{ route('manage.account.restore', $user->id) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin restore?')">
                                                    @csrf
                                                    <button
                                                        class="bg-green-500 text-white px-2 py-1 rounded text-sm">Restore</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
