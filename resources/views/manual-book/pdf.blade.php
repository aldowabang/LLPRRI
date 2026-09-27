<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manual Book — Sistem Manajemen Tugas LPP RRI Kupang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.55; color: #111; }
        .cover { text-align: center; padding-top: 170px; }
        .cover h1 { font-size: 22px; margin-bottom: 4px; }
        .cover h2 { font-size: 17px; margin-top: 0; color: #333; }
        .cover p { font-size: 12px; color: #555; }
        .cover .rule { border-bottom: 3px double #333; margin: 24px 90px; }
        .toc h2 { font-size: 16px; border-bottom: 2px solid #333; padding-bottom: 4px; }
        .toc ul { padding-left: 18px; }
        .toc li { margin-bottom: 3px; }
        .toc .section { font-weight: bold; margin-top: 8px; }
        .chapter { page-break-before: always; }
        .chapter.first { page-break-before: avoid; }
        .chapter .kicker { font-size: 11px; color: #666; margin-bottom: 0; }
        .chapter h1 { font-size: 17px; border-bottom: 2px solid #333; padding-bottom: 4px; margin-top: 2px; }
        .chapter h2 { font-size: 13px; margin-top: 14px; }
        .chapter h3 { font-size: 12px; margin-top: 10px; }
        .chapter table { width: 100%; border-collapse: collapse; margin: 8px 0; font-size: 10px; table-layout: fixed; word-wrap: break-word; }
        .chapter th, .chapter td { border: 1px solid #999; padding: 5px; text-align: left; vertical-align: top; }
        .chapter th { background-color: #eee; }
        .chapter tr { page-break-inside: avoid; }
        .chapter code { font-family: monospace; font-size: 10px; background: #f3f4f6; padding: 1px 4px; }
        .chapter pre { background: #f3f4f6; padding: 8px; font-size: 10px; white-space: pre-wrap; }
        .chapter pre code { background: none; padding: 0; }
        .chapter ul, .chapter ol { padding-left: 20px; }
        .chapter li { margin-bottom: 2px; }
        .screenshot-box { border: 1px dashed #666; background: #f9fafb; padding: 10px; margin: 8px 0; font-size: 10px; color: #444; }
        .page-break { page-break-before: always; }
        .sign { page-break-before: always; }
        .sign h2 { font-size: 15px; border-bottom: 2px solid #333; padding-bottom: 4px; }
        .sign-table { width: 100%; margin-top: 30px; }
        .sign-table td { width: 50%; text-align: center; font-size: 11px; vertical-align: top; }
        .footer-note { margin-top: 24px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="cover">
        <h1>LPP RRI KUPANG</h1>
        <div class="rule"></div>
        <h2>MANUAL BOOK</h2>
        <h2>Sistem Manajemen Tugas</h2>
        <p>Panduan penggunaan aplikasi berbasis peran<br>(Admin, Pimpinan, Pegawai)</p>
        <div class="rule"></div>
        <p>Dicetak: {{ $printedAt }}</p>
        <p>Sumber: docs/manual-book (satu berkas per tampilan)</p>
    </div>

    <div class="toc page-break">
        <h2>Daftar Isi</h2>
        <ul>
            @foreach ($chapters as $chapter)
                <li><strong>{{ $chapter['section'] }}</strong> — {{ $chapter['title'] }}</li>
            @endforeach
        </ul>
        <p class="footer-note">Setiap bab dimulai di halaman baru. Kotak berbingkai putus-putus menandai tempat screenshot aplikasi pada manual cetak.</p>
    </div>

    @foreach ($chapters as $index => $chapter)
        <div class="chapter {{ $index === 0 ? 'first' : '' }}">
            <p class="kicker">{{ $chapter['section'] }}</p>
            <h1>{{ $chapter['title'] }}</h1>
            {!! $chapter['html'] !!}
        </div>
    @endforeach

    <div class="sign">
        <h2>Lembar Pengesahan</h2>
        <p>Dokumen Manual Book Sistem Manajemen Tugas LPP RRI Kupang ini disusun berdasarkan tampilan dan alur aplikasi yang berjalan.</p>
        <table class="sign-table">
            <tr>
                <td>
                    Kupang, {{ $printedAt }}<br>
                    Kepala LPP RRI Kupang<br><br><br><br><br>
                    <strong><u>Yuliana Marta Doky, S.Sos</u></strong><br>
                    NIP 19690702 199903 2 002
                </td>
                <td>
                    <br>
                    Penyusun<br><br><br><br><br>
                    <strong><u>_________________________</u></strong><br>
                    &nbsp;
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
