@extends('layouts.head-of-division')

@section('title', 'Daftar SPB')

@section('page-title', 'Surat Permintaan Barang')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="spb-header mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">SPB</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">Daftar SPB</h1>
                    <p class="text-muted">Kelola pengajuan barang dari anggota divisi Anda.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpbModal">
                    <i class="fas fa-plus me-2"></i>Tambah SPB
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari nomor SPB atau proyek...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            <option value="workshop">Workshop</option>
                            <option value="site">Site</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Semua Proyek</option>
                            <option value="1">Rumah Tinggal A</option>
                            <option value="2">Gudang Logistik</option>
                            <option value="3">Perumahan Green Hill</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPB Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nomor SPB</th>
                                <th>Proyek</th>
                                <th>Diajukan Oleh</th>
                                <th>Tanggal SPB</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Pending SPB -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-2025-0012</span>
                                </td>
                                <td>Rumah Tinggal A</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Andi" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Andi</span>
                                    </div>
                                </td>
                                <td>2025-04-03</td>
                                <td><span class="badge bg-info">Site</span></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary" title="Lihat Detail" data-bs-toggle="modal"
                                            data-bs-target="#spbDetailModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Approved SPB -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-2025-0010</span>
                                </td>
                                <td>Gudang Logistik</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Budi" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Budi</span>
                                    </div>
                                </td>
                                <td>2025-03-28</td>
                                <td><span class="badge bg-info">Workshop</span></td>
                                <td><span class="badge bg-success">Disetujui</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" title="Lihat Detail" data-bs-toggle="modal"
                                        data-bs-target="#spbDetailModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Rejected SPB -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-2025-0009</span>
                                </td>
                                <td>Perumahan Green Hill</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Rina" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Rina</span>
                                    </div>
                                </td>
                                <td>2025-03-26</td>
                                <td><span class="badge bg-info">Site</span></td>
                                <td><span class="badge bg-danger">Ditolak</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" title="Lihat Detail" data-bs-toggle="modal"
                                        data-bs-target="#spbDetailModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile SPB Cards -->
        <div class="d-md-none mt-4">
            <!-- Mobile cards will be added here -->
        </div>
    </div>

    <!-- SPB Detail Modal -->
    <div class="modal fade" id="spbDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SPB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- SPB Header Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nomor SPB:</strong> SPB-2025-0012</p>
                            <p class="mb-1"><strong>Proyek:</strong> Rumah Tinggal A</p>
                            <p class="mb-1"><strong>Kategori:</strong> Site</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal:</strong> 2025-04-03</p>
                            <p class="mb-1"><strong>Diajukan Oleh:</strong> Andi</p>
                            <p class="mb-1"><strong>Status:</strong> <span
                                    class="badge bg-warning text-dark">Pending</span></p>
                        </div>
                    </div>

                    <!-- Item List -->
                    <h6 class="mb-3">Daftar Item</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                    <th>Status PO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Semen</td>
                                    <td>50</td>
                                    <td>Sak</td>
                                    <td>Untuk pengecoran lantai</td>
                                    <td><span class="badge bg-secondary">Belum PO</span></td>
                                </tr>
                                <tr>
                                    <td>Pasir</td>
                                    <td>2</td>
                                    <td>Truk</td>
                                    <td>Pasir halus</td>
                                    <td><span class="badge bg-success">Tersedia</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger">Tolak</button>
                    <button type="button" class="btn btn-success">Setujui</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add SPB Modal -->
    <div class="modal fade" id="addSpbModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah SPB Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="spbForm">
                        <!-- Common Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Proyek</label>
                                <select class="form-select" name="project_id" required>
                                    <option value="">Pilih Proyek</option>
                                    <option value="1">Rumah Tinggal A</option>
                                    <option value="2">Gudang Logistik</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tugas Terkait</label>
                                <select class="form-select" name="task_id">
                                    <option value="">Pilih Tugas (Opsional)</option>
                                    <option value="1">Pengecoran Lantai</option>
                                    <option value="2">Pemasangan Atap</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori Item</label>
                                <select class="form-select" name="item_category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1">Material</option>
                                    <option value="2">Peralatan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori Entry</label>
                                <select class="form-select" name="category_entry" id="categoryEntry" required>
                                    <option value="">Pilih Kategori Entry</option>
                                    <option value="site">Site</option>
                                    <option value="workshop">Workshop</option>
                                </select>
                            </div>
                        </div>

                        <!-- Site SPB Form -->
                        <div id="siteForm" style="display: none;">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Daftar Item Site</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addSiteItem">
                                        <i class="fas fa-plus"></i> Tambah Item
                                    </button>
                                </div>
                                <div id="siteItems">
                                    <!-- Item template will be cloned here -->
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dokumen Pendukung</label>
                                <input type="file" class="form-control" name="document_file[]" multiple>
                            </div>
                        </div>

                        <!-- Workshop SPB Form -->
                        <div id="workshopForm" style="display: none;">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Daftar Item Workshop</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addWorkshopItem">
                                        <i class="fas fa-plus"></i> Tambah Item
                                    </button>
                                </div>
                                <div id="workshopItems">
                                    <!-- Item template will be cloned here -->
                                </div>
                            </div>
                        </div>

                        <!-- Templates -->
                        <template id="siteItemTemplate">
                            <div class="row mb-2 site-item">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="item_name[]"
                                        placeholder="Nama Item" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" name="quantity[]" placeholder="Jumlah"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="unit[]" placeholder="Satuan"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="information[]"
                                        placeholder="Keterangan">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template id="workshopItemTemplate">
                            <div class="row mb-2 workshop-item">
                                <div class="col-md-5">
                                    <textarea class="form-control" name="explanation_items[]" placeholder="Penjelasan Item" required></textarea>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="quantity[]" placeholder="Jumlah"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="unit[]" placeholder="Satuan"
                                        required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveSpb">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryEntry = document.getElementById('categoryEntry');
            const siteForm = document.getElementById('siteForm');
            const workshopForm = document.getElementById('workshopForm');
            const addSiteItem = document.getElementById('addSiteItem');
            const addWorkshopItem = document.getElementById('addWorkshopItem');
            const siteItemTemplate = document.getElementById('siteItemTemplate');
            const workshopItemTemplate = document.getElementById('workshopItemTemplate');

            // Handle category entry change
            categoryEntry.addEventListener('change', function() {
                siteForm.style.display = this.value === 'site' ? 'block' : 'none';
                workshopForm.style.display = this.value === 'workshop' ? 'block' : 'none';
            });

            // Add site item
            addSiteItem.addEventListener('click', function(e) {
                e.preventDefault();
                const clone = siteItemTemplate.content.cloneNode(true);
                document.getElementById('siteItems').appendChild(clone);
            });

            // Add workshop item
            addWorkshopItem.addEventListener('click', function(e) {
                e.preventDefault();
                const clone = workshopItemTemplate.content.cloneNode(true);
                document.getElementById('workshopItems').appendChild(clone);
            });

            // Remove item
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item') || e.target.parentElement.classList.contains(
                        'remove-item')) {
                    const button = e.target.closest('.remove-item');
                    button.closest('.site-item, .workshop-item').remove();
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        @media (max-width: 768px) {
            .spb-card {
                background: white;
                border-radius: 10px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
@endpush
