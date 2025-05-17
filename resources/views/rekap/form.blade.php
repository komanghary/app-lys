<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Rekap Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <form action="{{ route('rekap.download') }}" method="GET" target="_blank">
                    <div class="mb-4">
                        <label for="user_id" class="block font-medium text-sm text-gray-700">Pilih Pegawai</label>
                        <select name="user_id" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="all">Semua Pegawai</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-4 pb-4">
                        <div class="w-1/2">
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Tanggal
                                Mulai</label>
                            <input type="date" name="start_date"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>
                        <div class="w-1/2">
                            <label for="end_date" class="block font-medium text-sm text-gray-700">Tanggal
                                Selesai</label>
                            <input type="date" name="end_date"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
