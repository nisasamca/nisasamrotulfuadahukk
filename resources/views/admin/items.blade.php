@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Items Data</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="bi bi-plus-lg me-1"></i> Add</button>
    </div>

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
                            <th scope="col" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Placeholder row -->
                        <tr>
                            <td>1</td>
                            <td>Laptop Dell XPS 13</td>
                            <td>Elektronik</td>
                            <td>Baik</td>
                            <td>Ruang IT</td>
                            <td>
            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editItemModal">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Kursi Kantor Ergonomis</td>
                            <td>Furniture</td>
                            <td>Baik</td>
                            <td>Ruang Umum</td>
                            <td>
            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editItemModal">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endsection