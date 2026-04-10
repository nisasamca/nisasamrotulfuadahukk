<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2;">No</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Nama </th>
            <th style="font-weight: bold; background-color: #f2f2f2;">kategori</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">kondisi</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">lokasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->category }}</td>
            <td>{{ $item->kondisi }}</td>
            <td>{{ $item->lokasi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>