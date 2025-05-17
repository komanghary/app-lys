<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Rekap Laporan</h1>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} sampai
        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Total Task</th>
                <th>Task Tepat Waktu</th>
                <th>Task Terlambat</th>
                <th>Task Revisi</th>
                <th>Hadir</th>
                <th>Alpha</th>
                <th>Izin</th>
                <th>Total Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['jabatan'] }}</td>
                    <td>{{ $item['task_total'] }}</td>
                    <td>{{ $item['task_tepat'] }}</td>
                    <td>{{ $item['task_telat'] }}</td>
                    <td>{{ $item['task_revisi'] }}</td>
                    <td>{{ $item['hadir'] }}</td>
                    <td>{{ $item['alpha'] }}</td>
                    <td>{{ $item['izin'] }}</td>
                    <td>{{ $item['total_poin'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
