@extends('layouts.staff')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data of lendings</h2>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLendingModal">
                <i class="bi bi-plus-lg me-1"></i> Add
            </button>
            <button class="btn btn-success ms-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
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
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Item</th>
                            <th scope="col">Total</th>
                            <th scope="col">Name</th>
                            <th scope="col">Ket.</th>
                            <th scope="col">Date</th>
                            <th scope="col">Returned</th>
                            <th scope="col">Edited By</th>
                            <th scope="col" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lendings as $lending)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($lending->details as $d)
                                        <li>{{ $d->item_name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($lending->details as $d)
                                        <li>{{ $d->total }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $lending->name }}</td>
                            <td>{{ $lending->ket }}</td>
                            <td>{{ $lending->created_at->format('d F, Y') }}</td>
                            <td>
                                @if($lending->is_returned)
                                    <span class="badge bg-success">returned</span>
                                @else
                                    <span class="badge bg-warning text-dark">not returned</span>
                                @endif
                            </td>
                            <td>{{ $lending->edited_by }}</td>
                            <td>
                                @if(!$lending->is_returned)
                                <form action="{{ route('lending.return', $lending->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success text-white mb-1"><i class="bi bi-check2"></i> Returned</button>
                                </form>
                                @endif
                                
                                <form action="{{ route('lending.delete', $lending->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white mb-1"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add Lending -->
    <div class="modal fade" id="addLendingModal" tabindex="-1" aria-labelledby="addLendingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('lending.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLendingModalLabel">Lending Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Please fill all input form with right value.</p>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="Name">
                        </div>

                        <!-- Dynamic Items Container -->
                        <div id="dynamic-items-container">
                            <div class="row mb-2 item-row">
                                <div class="col-md-7">
                                    <label class="form-label text-muted fw-bold">Items</label>
                                    <select class="form-select" name="items[]" required>
                                        <option value="" selected disabled>Select Items</option>
                                        <option value="Komputer">Komputer</option>
                                        <option value="Leptop">Leptop</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-bold">Total</label>
                                    <input type="number" class="form-control" name="total_items[]" required placeholder="total item">
                                </div>
                                <div class="col-md-1 d-flex align-items-end mb-1">
                                    <!-- Empty space for first row as no delete button -->
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 fw-bold" id="btn-more">
                                <i class="bi bi-chevron-down"></i> More
                            </button>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label text-muted fw-bold">Ket.</label>
                            <textarea class="form-control" name="ket" rows="3" required></textarea>
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnMore = document.getElementById('btn-more');
        const container = document.getElementById('dynamic-items-container');
        
        if (btnMore) {
            btnMore.addEventListener('click', function() {
                // Create new item row
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 item-row mt-3'; // mt-3 for spacing with the previous row
                newRow.innerHTML = `
                    <div class="col-md-7">
                        <select class="form-select" name="items[]" required>
                            <option value="" selected disabled>Select Items</option>
                            <option value="Komputer">Komputer</option>
                            <option value="Leptop">Leptop</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="total_items[]" required placeholder="total item">
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item border-0 fs-5 mt-1">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                `;
                
                container.appendChild(newRow);

                // Add event listener to the remove button
                newRow.querySelector('.btn-remove-item').addEventListener('click', function() {
                    newRow.remove();
                });
            });
        }
    });
</script>
@endpush