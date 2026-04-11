@extends('layouts.staff')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data of lendings</h2>
        <div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addLendingModal">
                <i class="bi bi-plus-lg me-1"></i> Add
            </button>
        </div>
    </div>

    <!-- Date Filter & Export Section -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('staff.lending') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark px-3">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('staff.lending') }}" class="btn btn-sm btn-outline-secondary px-3">
                        Reset
                    </a>
                    <button type="submit" name="export" value="true" class="btn btn-sm btn-success px-3 ms-auto" form="export-form">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </button>
                </div>
            </form>
            <!-- Separate form for export to keep filters -->
            <form id="export-form" action="{{ route('staff.lending.export') }}" method="GET" style="display:none;">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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
                            <th scope="col">Tgl Pinjam</th>
                            <th scope="col">Tgl Kembali</th>
                            <th scope="col">Ket.</th>
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
                            <td>{{ $lending->tanggal_pinjam ? \Carbon\Carbon::parse($lending->tanggal_pinjam)->format('d F, Y') : '-' }}</td>
                            <td>{{ $lending->tanggal_kembali ? \Carbon\Carbon::parse($lending->tanggal_kembali)->format('d F, Y') : '-' }}</td>
                            <td>{{ $lending->ket }}</td>
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
                                <button class="btn btn-sm btn-success text-white mb-1" data-bs-toggle="modal" data-bs-target="#returnModal{{ $lending->id }}">
                                    <i class="bi bi-check2"></i> Returned
                                </button>
                                @endif

                                @if($lending->is_returned)
                                <button class="btn btn-sm btn-info text-white mb-1" data-bs-toggle="modal" data-bs-target="#detailModal{{ $lending->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                @endif
                                
                                <form action="{{ route('staff.lending.delete', $lending->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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

    {{-- ===== SEMUA MODAL DI LUAR TABEL ===== --}}
    @foreach ($lendings as $lending)

    {{-- Modal Return (Kondisi per Item) --}}
    @if(!$lending->is_returned)
    <div class="modal fade" id="returnModal{{ $lending->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('staff.lending.return', $lending->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-arrow-return-left me-2"></i>Konfirmasi Pengembalian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">Tentukan kondisi setiap unit yang dikembalikan oleh <strong>{{ $lending->name }}</strong>.</p>
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th width="15%" class="text-center">Total</th>
                                    <th width="25%" class="text-center">Kondisi Baik</th>
                                    <th width="25%" class="text-center">Kondisi Rusak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lending->details as $d)
                                    <tr class="item-return-row" data-total="{{ $d->total }}">
                                        <td class="small">{{ $d->item_name }}</td>
                                        <td class="text-center fw-bold">{{ $d->total }}</td>
                                        <td>
                                            <input type="number" name="baik[{{ $d->id }}]" 
                                                class="form-control form-control-sm input-baik" 
                                                value="{{ $d->total }}" min="0" max="{{ $d->total }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="rusak[{{ $d->id }}]" 
                                                class="form-control form-control-sm input-rusak" 
                                                value="0" min="0" max="{{ $d->total }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="alert alert-info py-2 small mb-0">
                            <i class="bi bi-info-circle me-1"></i> 
                            Pastikan jumlah <strong>Baik + Rusak</strong> sama dengan total item yang dipinjam.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check2"></i> Konfirmasi Return</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal Detail (setelah dikembalikan) --}}
    @if($lending->is_returned)
    <div class="modal fade" id="detailModal{{ $lending->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-clipboard2-check me-2"></i>Detail Peminjaman</h5>
                    <div class="ms-auto d-flex gap-2 align-items-center">
                        <button type="button" 
                            class="btn btn-sm btn-danger btn-open-signature" 
                            data-pdf-url="{{ route('staff.lending.pdf', $lending->id) }}"
                            data-bs-dismiss="modal">
                            <i class="bi bi-pen me-1"></i>Tanda Tangan & PDF
                        </button>
                        <button type="button" class="btn-close ms-1" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row mb-3 g-2">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Nama Peminjam</p>
                            <p class="fw-bold">{{ $lending->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Diproses Oleh</p>
                            <p class="fw-bold">{{ $lending->edited_by }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Tanggal Pinjam</p>
                            <p class="fw-bold">{{ $lending->tanggal_pinjam ? \Carbon\Carbon::parse($lending->tanggal_pinjam)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Tanggal Kembali</p>
                            <p class="fw-bold">{{ $lending->tanggal_kembali ? \Carbon\Carbon::parse($lending->tanggal_kembali)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-muted small">Keterangan</p>
                            <p class="fw-bold">{{ $lending->ket }}</p>
                        </div>
                    </div>
                    <hr>
                    <p class="fw-bold mb-2">Daftar Barang</p>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th width="20%" class="text-center">Total Item</th>
                                <th width="40%" class="text-center">Kondisi Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lending->details as $d)
                                <tr>
                                    <td>{{ $d->item_name }}</td>
                                    <td class="text-center fw-bold">{{ $d->total }}</td>
                                    <td class="text-center">
                                        @if(is_array($d->kondisi_kembali) && isset($d->kondisi_kembali['baik']))
                                            <span class="badge bg-success">{{ $d->kondisi_kembali['baik'] }} Baik</span>
                                            @if($d->kondisi_kembali['rusak'] > 0)
                                                <span class="badge bg-danger">{{ $d->kondisi_kembali['rusak'] }} Rusak</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endforeach

    <!-- Modal Add Lending -->
    <div class="modal fade" id="addLendingModal" tabindex="-1" aria-labelledby="addLendingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('staff.lending.store') }}" method="POST">
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
                                    <select class="form-select text-dark" name="items[]" required>
                                        <option value="" disabled selected>Select Items</option>

                                        @foreach ($items as $item)
                                            <option value="{{ $item->nama }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
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

                        <div class="row mb-3 mt-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Tanggal Pinjam</label>
                                <input type="date" class="form-control" name="tanggal_pinjam" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Tanggal Kembali</label>
                                <input type="date" class="form-control" name="tanggal_kembali">
                            </div>
                        </div>

                        <div class="mb-3">
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
    <!-- Modal Signature -->
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tanda Tangan Peminjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-muted small mb-2">Silakan tanda tangan di dalam kotak di bawah ini.</p>
                    <div style="border: 1px solid #ccc; background: #fff;">
                        <canvas id="signature-pad" width="450" height="200"></canvas>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-signature">Hapus</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="save-pdf">Download PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for PDF Export with Signature -->
    <form id="pdf-signature-form" action="" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="signature" id="signature-input">
    </form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dynamic Items logic (already exists)
        const selectElement = document.querySelector('select[name="items[]"]');
        if (selectElement) {
            const options = selectElement.innerHTML;
            const btnMore = document.getElementById('btn-more');
            const container = document.getElementById('dynamic-items-container');
            
            if (btnMore) {
                btnMore.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.className = 'row mb-2 item-row mt-3';
                    newRow.innerHTML = `
                        <div class="col-md-7">
                            <select class="form-select" name="items[]" required>
                                ${options}
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
                    newRow.querySelector('.btn-remove-item').addEventListener('click', function() {
                        newRow.remove();
                    });
                });
            }
        }

        // Bulk Return Logic (Auto-sync counts and validate)
        document.querySelectorAll('.item-return-row').forEach(row => {
            const total = parseInt(row.getAttribute('data-total'));
            const inputBaik = row.querySelector('.input-baik');
            const inputRusak = row.querySelector('.input-rusak');

            inputBaik.addEventListener('input', function() {
                let val = parseInt(this.value) || 0;
                if (val > total) { val = total; this.value = total; }
                if (val < 0) { val = 0; this.value = 0; }
                inputRusak.value = total - val;
            });

            inputRusak.addEventListener('input', function() {
                let val = parseInt(this.value) || 0;
                if (val > total) { val = total; this.value = total; }
                if (val < 0) { val = 0; this.value = 0; }
                inputBaik.value = total - val;
            });
        });

        // Signature Pad Logic
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        const clearBtn = document.getElementById('clear-signature');
        const savePdfBtn = document.getElementById('save-pdf');
        const signatureModal = new bootstrap.Modal(document.getElementById('signatureModal'));
        let currentPdfUrl = '';

        // Open Signature Modal
        document.querySelectorAll('.btn-open-signature').forEach(btn => {
            btn.addEventListener('click', function() {
                currentPdfUrl = this.getAttribute('data-pdf-url');
                signaturePad.clear();
                signatureModal.show();
            });
        });

        clearBtn.addEventListener('click', function() {
            signaturePad.clear();
        });

        savePdfBtn.addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
                alert("Silakan tanda tangan terlebih dahulu!");
                return;
            }

            const signatureData = signaturePad.toDataURL();
            const form = document.getElementById('pdf-signature-form');
            const signatureInput = document.getElementById('signature-input');

            form.action = currentPdfUrl;
            signatureInput.value = signatureData;
            
            signatureModal.hide();
            form.submit();
        });
    });
</script>
@endpush