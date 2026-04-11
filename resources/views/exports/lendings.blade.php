<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2;">No</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Peminjam</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Tgl Pinjam</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Tgl Kembali</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Keterangan</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Status</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Barang Dipinjam</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lendings as $lending)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $lending->name }}</td>
            <td>{{ $lending->tanggal_pinjam }}</td>
            <td>{{ $lending->tanggal_kembali }}</td>
            <td>{{ $lending->ket }}</td>
            <td>{{ $lending->is_returned ? 'Dikembalikan' : 'Belum Kembali' }}</td>
            <td>
                @foreach ($lending->details as $d)
                    {{ $d->item_name }} ({{ $d->total }})@if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
