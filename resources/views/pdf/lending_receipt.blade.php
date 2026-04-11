<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman #{{ $lending->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; padding: 30px; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 14px; border-bottom: 2px solid #2563eb; }
        .header h1 { font-size: 20px; color: #1e40af; margin-bottom: 4px; }
        .header p { color: #6b7280; font-size: 11px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .info-grid { display: table; width: 100%; margin-bottom: 22px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 130px; padding: 4px 0; color: #6b7280; }
        .info-value { display: table-cell; padding: 4px 0; font-weight: 600; }
        .section-title { font-size: 13px; font-weight: bold; color: #1e40af; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px solid #dbeafe; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #eff6ff; color: #1e40af; padding: 9px 10px; text-align: left; font-size: 11px; border: 1px solid #bfdbfe; }
        td { padding: 8px 10px; border: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .kondisi-baik { color: #065f46; font-weight: bold; }
        .kondisi-rusak { color: #b45309; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px; }
        .sign-area { display: table; width: 100%; margin-top: 40px; }
        .sign-box { display: table-cell; width: 50%; text-align: center; padding: 0 20px; }
        .sign-line { margin-top: 50px; border-top: 1px solid #6b7280; padding-top: 4px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STRUK PEMINJAMAN BARANG</h1>
        <p>Sistem Inventaris &bull; {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">No. Peminjaman</div>
            <div class="info-value">: #{{ str_pad($lending->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama Peminjam</div>
            <div class="info-value">: {{ $lending->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Pinjam</div>
            <div class="info-value">: {{ $lending->tanggal_pinjam ? \Carbon\Carbon::parse($lending->tanggal_pinjam)->format('d F Y') : '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Kembali</div>
            <div class="info-value">: {{ $lending->tanggal_kembali ? \Carbon\Carbon::parse($lending->tanggal_kembali)->format('d F Y') : '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Keterangan</div>
            <div class="info-value">: {{ $lending->ket }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">: 
                <span class="badge {{ $lending->is_returned ? 'badge-success' : 'badge-warning' }}">
                    {{ $lending->is_returned ? 'Sudah Dikembalikan' : 'Belum Dikembalikan' }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Diproses Oleh</div>
            <div class="info-value">: {{ $lending->edited_by }}</div>
        </div>
    </div>

    <div class="section-title">Daftar Barang Yang Dipinjam</div>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th width="15%" style="text-align: center;">Total</th>
                <th width="35%">Kondisi Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lending->details as $d)
                <tr>
                    <td>{{ $d->item_name }}</td>
                    <td style="text-align: center;">{{ $d->total }} unit</td>
                    <td>
                        @if(is_array($d->kondisi_kembali) && isset($d->kondisi_kembali['baik']))
                            <span class="kondisi-baik">{{ $d->kondisi_kembali['baik'] }} Baik</span>
                            @if($d->kondisi_kembali['rusak'] > 0)
                                , <span class="kondisi-rusak">{{ $d->kondisi_kembali['rusak'] }} Rusak</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="sign-area">
        <div class="sign-box">
            @if(!empty($signature))
                <img src="{{ $signature }}" style="max-height:60px; max-width:160px; display:block; margin: 0 auto 4px auto;">
            @else
                <div style="height:60px;"></div>
            @endif
            <div class="sign-line">Peminjam<br><strong>( {{ $lending->name }} )</strong></div>
        </div>
        <div class="sign-box">
            <div style="height:60px;"></div>
            <div class="sign-line">Petugas Inventaris<br><strong>( {{ $lending->edited_by }} )</strong></div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dicetak otomatis oleh Sistem Inventaris &bull; {{ now()->format('d F Y') }}
    </div>
</body>
</html>
