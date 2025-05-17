<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kalender Presensi
        </h2>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                @if (auth()->user()->role == 1)
                    @if (!$sudahPresensi)
                        <form method="POST" action="{{ route('presensi.store') }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Presensi Masuk
                            </button>
                        </form>
                    @else
                        <p class="text-green-600 font-semibold">Anda sudah presensi hari ini.</p>
                    @endif
                @else
                    @if (auth()->user()->role == 2)
                        <form method="POST" action="{{ route('presensi.store') }}" class="mb-6">
                            @csrf
                            <div class="flex flex-wrap items-end gap-4">
                                <div class="flex-1">
                                    <label for="identitas_id" class="block text-sm font-medium">Pegawai</label>
                                    <select name="identitas_id" id="identitas_id"
                                        class="border-gray-300 rounded w-full">
                                        @foreach (\App\Models\User::where('role', 1)->get() as $pegawai)
                                            <option value="{{ $pegawai->id }}">{{ $pegawai->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        class="border-gray-300 rounded w-full" required>
                                </div>
                                <div class="flex-1">
                                    <label for="status" class="block text-sm font-medium">Status</label>
                                    <select name="status" id="status" class="border-gray-300 rounded w-full">
                                        <option value="1">Hadir</option>
                                        <option value="2">Izin</option>
                                        <option value="3">Sakit</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label for="keterangan" class="block text-sm font-medium">Keterangan
                                        (Opsional)</label>
                                    <input type="text" name="keterangan" id="keterangan"
                                        class="border-gray-300 rounded w-full">
                                </div>
                                <div>
                                    <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Tambah Presensi
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if (auth()->user()->role != 1 && isset($belumPresensiCount))
                        <div class="mb-4 text-red-600 font-semibold">
                            Belum presensi hari ini: {{ $belumPresensiCount }} orang
                        </div>
                    @endif
                @endif
                <hr class="my-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Script Section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>
<script>
    const userRole = {{ $userRole }};
    // Fungsi untuk mendapatkan label status
    function statusLabel(status) {
        switch (parseInt(status)) {
            case 1:
                return "Hadir";
            case 2:
                return "Izin";
            case 3:
                return "Sakit";
            default:
                return "Tidak diketahui";
        }
    }

    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: {!! $presensis->map(function ($presensi) {
                    $title = auth()->user()->role == 1 ? 'Presensi Saya' : $presensi->user->name;
                    if ($presensi->status == 2) {
                        $title .= ' (Izin';
                        if ($presensi->keterangan) {
                            $title .= ': ' . $presensi->keterangan;
                        }
                        $title .= ')';
                    } elseif ($presensi->status == 3) {
                        $title .= ' (Sakit';
                        if ($presensi->keterangan) {
                            $title .= ': ' . $presensi->keterangan;
                        }
                        $title .= ')';
                    }
            
                    switch ($presensi->status) {
                        case 1:
                            $color = '#28a745'; // hijau
                            break;
                        case 2:
                            $color = '#ffc107'; // kuning
                            break;
                        case 3:
                            $color = '#dc3545'; // merah
                            break;
                        default:
                            $color = '#6c757d'; // abu
                    }
                    /*1 => '#28a745', // hijau
                                                                                                                                                                                                                        2 => '#ffc107', // kuning
                                                                                                                                                                                                                        3 => '#dc3545', // merah
                                                                                                                                                                                                                        default => '#6c757d', // abu
                                                                                                                                                                                                                    };*/
            
                    return [
                        'id' => $presensi->id,
                        'title' => $title,
                        'start' => $presensi->tanggal,
                        'allDay' => true,
                        'status' => $presensi->status,
                        'keterangan' => $presensi->keterangan,
                        'color' => $color,
                    ];
                })->toJson() !!},

            // Klik pada event untuk update status
            eventClick: function(event) {
                if (userRole === 1) {
                    return; // Pegawai tidak bisa klik untuk ubah
                }

                let message = `Status saat ini: ${statusLabel(event.status)}\n`;
                if (event.keterangan) {
                    message += `Keterangan: ${event.keterangan}\n`;
                }
                message += "Masukkan status baru:\n1 = Hadir\n2 = Izin\n3 = Sakit";

                const newStatus = prompt(message, event.status);
                if (newStatus === null) return;

                if (![1, 2, 3].includes(parseInt(newStatus))) {
                    alert('Status tidak valid.');
                    return;
                }

                // Kirim AJAX update
                $.ajax({
                    url: `/presensi/${event.id}`,
                    method: 'PUT',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        let newColor = '';
                        if (newStatus == 1) newColor = '#28a745';
                        else if (newStatus == 2) newColor = '#ffc107';
                        else if (newStatus == 3) newColor = '#dc3545';

                        event.color = newColor;
                        event.status = newStatus;
                        event.title =
                            `${event.title.split(':')[1].trim()} (${statusLabel(newStatus)})`;

                        $('#calendar').fullCalendar('updateEvent', event);
                        alert('Status berhasil diperbarui.');
                    },
                    error: function() {
                        alert('Gagal memperbarui status.');
                    }
                });
            }
        });
    });
</script>
