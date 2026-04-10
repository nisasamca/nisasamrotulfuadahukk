<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2;">No</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Nama Kategori</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Division</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">PJ (Penanggung Jawab)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $category->nama }}</td>
            <td>{{ $category->division }}</td>
            <td>{{ $category->pj }}</td>
        </tr>
        @endforeach
    </tbody>
</table>