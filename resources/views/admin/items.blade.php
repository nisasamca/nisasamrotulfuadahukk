@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Items Data</h2>
        <div class="d-flex">
            <a href="{{ route('items.export') }}" class="btn btn-success me-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export
            </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="bi bi-plus-lg me-1"></i> 
            Add
        </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Kondisi</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Total Item</th>
                            <th scope="col" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->category->nama ?? '-'}}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->total_item }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                    <i class="bi bi-pencil-square me-1"></i> 
                                </button>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- modaledit --}}
                        <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Item</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <label class="form-label small text-muted mb-0">Nama Item</label>
                                                <input type="text" name="nama" value="{{ $item->nama }}" class="form-control mb-2">

                                                <label class="form-label small text-muted mb-0">Kategori</label>
                                                <select name="kategori_id" class="form-select mb-2">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" 
                                                            {{ $item->kategori_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <label class="form-label small text-muted mb-0">Kondisi</label>
                                                <select name="kondisi" class="form-select mb-2">
                                                    <option value="baik" {{ $item->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                                    <option value="rusak" {{ $item->kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                                </select>

                                                <label class="form-label small text-muted mb-0">Total Item</label>
                                                <input type="number" name="total_item" value="{{ $item->total_item }}" class="form-control mb-2" required>

                                                <label class="form-label small text-muted mb-0">Jumlah Repair</label>
                                                <input type="number" name="jumlah_repair" value="{{ $item->jumlah_repair }}" class="form-control mb-2" required>

                                                <label class="form-label small text-muted mb-0">Lokasi</label>
                                                <input type="text" name="lokasi" value="{{ $item->lokasi }}" class="form-control">
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Item</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kondisi</label>
                            <select class="form-select" name="kondisi" required>
                                <option value="" selected disabled>Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Item</label>
                            <input type="number" class="form-control" name="total_item" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Repair</label>
                            <input type="number" class="form-control" name="jumlah_repair" value="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection