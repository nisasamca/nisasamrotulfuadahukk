@extends('layouts.staff')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Items Data</h2>
        <div class="d-flex">
            <a href="{{ route('items.export') }}" class="btn btn-success mb-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Total</th>
                            <th scope="col">Baik</th>
                            <th scope="col">Rusak</th>
                            <th scope="col">Available</th>
                            <th scope="col">Lending</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->category->nama ?? '-' }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->total_item }}</td>
                            <td>
                                <span>{{ $item->baik }}</span>
                            </td>
                            <td>
                                @if($item->rusak > 0)
                                    <span >{{ $item->rusak }}</span>
                                @else
                                    <span >0</span>
                                @endif
                            </td>
                            <td>
                                @if($item->available > 0)
                                    <span class="badge bg-success">{{ $item->available }}</span>
                                @else
                                    <span class="badge bg-danger">0</span>
                                @endif
                            </td>
                            <td>{{ $item->lending }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection