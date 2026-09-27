<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Produksi - {{ $tugas->nama_tugas }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 5px;
        }
        .institution-title {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
        }
        .doc-number {
            font-size: 10pt;
            margin-top: 2px;
        }
        .preamble {
            margin-top: 20px;
            margin-bottom: 15px;
            text-align: justify;
        }
        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-left: 15px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 30px;
            margin-bottom: 15px;
        }
        .details-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .details-table td.num {
            width: 25px;
        }
        .details-table td.label {
            width: 180px;
        }
        .details-table td.colon {
            width: 15px;
        }
        .closing {
            margin-top: 20px;
            margin-bottom: 25px;
            text-align: justify;
        }
        .signature-block {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .qrcode-box {
            margin: 10px auto;
            width: 80px;
            height: 80px;
            border: 1px dashed #666;
            padding: 5px;
        }
        .qrcode-box img {
            width: 100%;
            height: 100%;
        }
        .signer-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
        }
        .signer-nip {
            font-size: 9.5pt;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="institution-title">RADIO REPUBLIK INDONESIA</div>
        <div class="doc-title">NOTA PRODUKSI</div>
        <div class="doc-number">{{ $tugas->nomor_nota ?? '1198/RRI.KPG/XVII.PPS.01.02/07/2025' }}</div>
    </div>

    <div class="preamble">
        Kepala RRI Kupang dengan ini memberikan tugas kepada Tim/Kerabat Kerja sebagaimana tercantum pada angka 2 (dua) untuk melaksanakan Tugas Produksi {{ $tugas->nama_tugas }} pada angka 1 (satu) sebagai berikut:
    </div>

    <!-- 1. Acara -->
    <div class="section-title">1. Acara</div>
    <table class="details-table">
        <tr>
            <td class="num">a.</td>
            <td class="label">Nama Acara</td>
            <td class="colon">:</td>
            <td><strong>{{ $tugas->nama_tugas }}</strong></td>
        </tr>
        <tr>
            <td class="num">b.</td>
            <td class="label">Format Acara</td>
            <td class="colon">:</td>
            <td>{{ $tugas->format }}</td>
        </tr>
        <tr>
            <td class="num">c.</td>
            <td class="label">Disiarkan</td>
            <td class="colon">:</td>
            <td>{{ $tugas->disiarkan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="num">d.</td>
            <td class="label">Rapat Pra-Produksi</td>
            <td class="colon">:</td>
            <td>
                @if($tugas->tanggal_rapat)
                    {{ \Carbon\Carbon::parse($tugas->tanggal_rapat)->isoFormat('dddd, D MMMM YYYY') }}
                    {{ $tugas->waktu_rapat ? 'pukul ' . substr($tugas->waktu_rapat, 0, 5) . ' WITA' : '' }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="num">e.</td>
            <td class="label">Tempat</td>
            <td class="colon">:</td>
            <td>{{ $tugas->tempat_rapat ?? 'Ruang Siaran' }}</td>
        </tr>
        <tr>
            <td class="num">f.</td>
            <td class="label">Produksi</td>
            <td class="colon">:</td>
            <td>
                @if($tugas->tanggal_produksi)
                    {{ \Carbon\Carbon::parse($tugas->tanggal_produksi)->isoFormat('dddd, D MMMM YYYY') }}
                    {{ $tugas->waktu_produksi ? 'pukul ' . substr($tugas->waktu_produksi, 0, 5) . ' WITA' : '' }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="num">g.</td>
            <td class="label">Tempat</td>
            <td class="colon">:</td>
            <td>{{ $tugas->tempat_produksi ?? $tugas->tempat ?? 'Studio 1' }}</td>
        </tr>
        <tr>
            <td class="num">h.</td>
            <td class="label">Narasumber</td>
            <td class="colon">:</td>
            <td>{{ $tugas->narasumber ?? '-' }}</td>
        </tr>
        <tr>
            <td class="num">i.</td>
            <td class="label">Topik</td>
            <td class="colon">:</td>
            <td>{{ $tugas->topik }}</td>
        </tr>
    </table>

    <!-- 2. Tim/Kerabat Kerja -->
    <div class="section-title">2. Tim/Kerabat Kerja</div>
    <table class="details-table">
        <tr>
            <td class="num">a.</td>
            <td class="label">Penanggung Jawab Umum</td>
            <td class="colon">:</td>
            <td>{{ $tugas->penanggung_jawab ?? 'Kepala LPP RRI Kupang' }}</td>
        </tr>
        <tr>
            <td class="num">b.</td>
            <td class="label">Supervisor</td>
            <td class="colon">:</td>
            <td>{{ $tugas->supervisor ?? 'Kabag TU dan Para Ketua Tim' }}</td>
        </tr>
        <tr>
            <td class="num">c.</td>
            <td class="label">Produser</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('produser') }}</td>
        </tr>
        <tr>
            <td class="num">d.</td>
            <td class="label">Asisten Produser</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('asisten_produser') }}</td>
        </tr>
        <tr>
            <td class="num">e.</td>
            <td class="label">Pengarah Acara</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('pengarah_acara') }}</td>
        </tr>
        <tr>
            <td class="num">f.</td>
            <td class="label">Asisten PA</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('asisten_pa') }}</td>
        </tr>
        <tr>
            <td class="num">g.</td>
            <td class="label">Presenter</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('presenter') }}</td>
        </tr>
        <tr>
            <td class="num">h.</td>
            <td class="label">Cameraman</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('cameraman') }}</td>
        </tr>
        <tr>
            <td class="num">i.</td>
            <td class="label">Teknisi</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('teknisi') }}</td>
        </tr>
        <tr>
            <td class="num">j.</td>
            <td class="label">Editor</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('editor') }}</td>
        </tr>
        <tr>
            <td class="num">k.</td>
            <td class="label">Dokumentasi/ Publikasi</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('dokumentasi') }}</td>
        </tr>
        <tr>
            <td class="num">l.</td>
            <td class="label">Unit Manager</td>
            <td class="colon">:</td>
            <td>{{ $tugas->getCrewNamesByRole('unit_manager') }}</td>
        </tr>
    </table>

    <div class="closing">
        Dalam melaksanakan tugasnya, wajib melakukan koordinasi dan bertanggung jawab kepada Kepala RRI Kupang.<br>
        Demikian Nota Produksi ini dibuat untuk dilaksanakan sebagaimana mestinya.
    </div>

    <div class="signature-block">
        <div class="signature-title">Kepala LPP RRI Kupang,</div>
        
        <!-- QR Code Placeholder / Authentic Seal -->
        <div class="qrcode-box">
            @php
                $qrData = "Nota Produksi LPP RRI Kupang\nNo: " . ($tugas->nomor_nota ?? '-') . "\nJudul: " . $tugas->nama_tugas . "\nDisetujui: Kepala LPP RRI Kupang";
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($qrData);
            @endphp
            <img src="{{ $qrUrl }}" alt="QR Validation">
        </div>

        <div class="signer-name">Yuliana Marta Doky, S.Sos</div>
        <div class="signer-nip">NIP. 19690702 199903 2 002</div>
    </div>

</body>
</html>
