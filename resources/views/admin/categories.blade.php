@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Categories Data</h2>

        <div class="d-flex">
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg me-1"></i> Add
            </button>

            <a href="{{ route('categories.export') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
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
                            <th scope="col">Division</th>
                            <th scope="col">PJ</th>
                            <th scope="col">Total Item</th>
                            <th scope="col" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->nama }}</td>
                            <td>{{ $category->division }}</td>
                            <td>{{ $category->pj }}</td>
                            <td>{{ $category->items_count }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white edit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editCategoryModal"
                                        data-id="{{ $category->id }}"
                                        data-nama="{{ $category->nama }}"
                                        data-division="{{ $category->division }}"
                                        data-pj="{{ $category->pj }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Division</label>
                            <select class="form-select" name="division" required>
                                <option value="" selected disabled>Pilih Divisi</option>
                                <option value="Sarpras">Sarpras</option>
                                <option value="TU">TU</option>
                                <option value="Tefa">Tefa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PJ (Penanggung Jawab)</label>
                            <input type="text" class="form-control" name="pj" required>
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

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Division</label>
                            <select class="form-select" id="edit_division" name="division" required>
                                <option value="Sarpras">Sarpras</option>
                                <option value="TU">TU</option>
                                <option value="Tefa">Tefa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PJ (Penanggung Jawab)</label>
                            <input type="text" class="form-control" id="edit_pj" name="pj" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            const editForm = document.getElementById('editForm');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const division = this.getAttribute('data-division');
                    const pj = this.getAttribute('data-pj');

                    // Update action URL: /categories/{id}
                    editForm.action = `/categories/${id}`;
                    
                    // Isi field input
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_division').value = division;
                    document.getElementById('edit_pj').value = pj;
                });
            });
        });
    </script>
@endsection