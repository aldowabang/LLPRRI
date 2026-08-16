<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        h2 { text-align: center; font-size: 14px; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-gray { background: #e5e7eb; color: #374151; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LPP RRI KUPANG</h1>
        <h2>Laporan Data Tugas</h2>
        <p style="font-size: 11px; color: #888;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tugas</th>
                <th>Pegawai</th>
                <th>Unit</th>
                <th>Format</th>
                <th>Tanggal Produksi</th>
                <th>Tempat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tugases as $tugas)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tugas->nama_tugas }}</td>
                    <td>{{ $tugas->pegawai->nama_pegawai ?? '-' }}</td>
                    <td>{{ $tugas->pegawai->unit->nama_unit ?? '-' }}</td>
                    <td>{{ $tugas->format }}</td>
                    <td>{{ $tugas->tanggal_produksi->format('d/m/Y') }}</td>
                    <td>{{ $tugas->tempat }}</td>
                    <td>
                        @switch($tugas->status_tugas)
                            @case('belum_mulai')
                                <span class="badge badge-gray">Belum Mulai</span>
                                @break
                            @case('proses')
                                <span class="badge badge-yellow">Proses</span>
                                @break
                            @case('selesai')
                                <span class="badge badge-blue">Selesai</span>
                                @break
                            @case('acc')
                                <span class="badge badge-green">ACC</span>
                                @break
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data tugas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Kupang, {{ now()->format('d/m/Y') }}</p>
        <p style="margin-top: 30px;">_________________________<br>Kepala LPP RRI Kupang</p>
    </div>
</body>
</html>
