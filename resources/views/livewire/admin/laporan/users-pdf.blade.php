<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data User - LPP RRI Kupang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        h2 { text-align: center; font-size: 14px; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 10px; vertical-align: top; }
        th { background-color: #f5f5f5; font-weight: bold; }
        tr { page-break-inside: avoid; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-gray { background: #e5e7eb; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LPP RRI KUPANG</h1>
        <h2>Laporan Data User (Akun Login)</h2>
        <p style="font-size: 11px; color: #888;">Dicetak: {{ now()->format('d/m/Y H:i') }} — Total: {{ $users->count() }} akun</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 15%;">Nama</th>
                <th style="width: 18%;">Email</th>
                <th style="width: 8%;">Role</th>
                <th style="width: 14%;">Pegawai</th>
                <th style="width: 11%;">NIP</th>
                <th style="width: 10%;">Unit</th>
                <th style="width: 10%;">Jabatan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($user->role)
                            @case('admin')
                                <span class="badge badge-red">Admin</span>
                                @break
                            @case('pimpinan')
                                <span class="badge badge-blue">Pimpinan</span>
                                @break
                            @default
                                <span class="badge badge-green">Pegawai</span>
                        @endswitch
                    </td>
                    <td>{{ $user->pegawai->nama_pegawai ?? '-' }}</td>
                    <td>{{ $user->pegawai->nip ?? '-' }}</td>
                    <td>{{ $user->pegawai->unit->nama_unit ?? '-' }}</td>
                    <td>{{ $user->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                    <td>
                        @if ($user->email_verified_at)
                            <span class="badge badge-green">Terverifikasi</span>
                        @else
                            <span class="badge badge-gray">Belum</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Belum ada data user.</td>
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
