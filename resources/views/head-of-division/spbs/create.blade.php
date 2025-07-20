@extends('layouts.head-of-division')

@section('title', 'Buat SPB')

@section('content')
    <div class="container-fluid" x-data="spbForm">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Buat SPB Baru</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('head-of-division.spbs.index') }}">SPB</a></li>
                        <li class="breadcrumb-item active">Buat</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('head-of-division.spbs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar SPB
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('head-of-division.spbs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <!-- Main Form Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Tanggal SPB</label>
                                    <input type="date" name="spb_date"
                                        class="form-control @error('spb_date') is-invalid @enderror"
                                        value="{{ old('spb_date', date('Y-m-d')) }}" required>
                                    @error('spb_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Estimasi Tanggal Pakai</label>
                                    <input type="date" name="estimasi_pakai"
                                        class="form-control @error('estimasi_pakai') is-invalid @enderror"
                                        value="{{ old('estimasi_pakai') }}">
                                    @error('estimasi_pakai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Proyek</label>
                                    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror"
                                        x-on:change="getProjectTasks($event.target.value)" required
                                        {{ $task ? 'disabled' : '' }}>
                                        <option value="">Pilih Proyek</option>
                                        @foreach ($projects as $id => $name)
                                            <option value="{{ $id }}" @selected(old('project_id', $task?->project_id) == $id)>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($task)
                                        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                                    @endif
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Tugas</label>
                                    <select name="task_id" class="form-select @error('task_id') is-invalid @enderror"
                                        required {{ $task ? 'disabled' : '' }} x-model="selectedTask">
                                        <option value="">Pilih Tugas</option>
                                        <template x-for="task in tasks" :key="task.id">
                                            <option :value="task.id"
                                                x-text="task.name + ' (' + task.status_label + ')'">
                                            </option>
                                        </template>
                                    </select>
                                    @if ($task)
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    @endif
                                    @error('task_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Kategori Item</label>
                                    <select name="item_category_id"
                                        class="form-select @error('item_category_id') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($itemCategories as $id => $name)
                                            <option value="{{ $id }}" @selected(old('item_category_id') == $id)>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('item_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label required">Jenis Permintaan</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input type="radio" name="category_entry" value="site"
                                                class="form-check-input @error('category_entry') is-invalid @enderror"
                                                x-model="category" @checked(old('category_entry', 'site') === 'site')>
                                            <label class="form-check-label">Site</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="category_entry" value="workshop"
                                                class="form-check-input @error('category_entry') is-invalid @enderror"
                                                x-model="category" @checked(old('category_entry') === 'workshop')>
                                            <label class="form-check-label">Workshop</label>
                                        </div>
                                    </div>
                                    @error('category_entry')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Card -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daftar Item</h5>
                            <button type="button" class="btn btn-sm btn-primary" x-on:click="addItem()">
                                <i class="fas fa-plus me-2"></i>Tambah Item
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Site Items Form -->
                            <template x-if="category === 'site'">
                                <div class="site-items">
                                    <template x-for="(item, index) in siteItems" :key="index">
                                        <div class="border rounded p-3 mb-3">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label required">Nama Item</label>
                                                    <select :name="'site_items[' + index + '][item_name]'"
                                                        class="form-select" x-model="item.item_name" required
                                                        @change="onItemChange(index, 'site')">
                                                        <option value="">Pilih Item</option>
                                                        <template x-for="taskItem in taskItems" :key="taskItem.id">
                                                            <option :value="taskItem.nama_barang"
                                                                x-text="taskItem.nama_barang"
                                                                :disabled="siteItems.some((i, idx) => i.item_name === taskItem
                                                                    .nama_barang && idx !== index)">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Jumlah</label>
                                                    <input type="number" :name="'site_items[' + index + '][quantity]'"
                                                        class="form-control" x-model="item.quantity" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Satuan</label>
                                                    <input type="text" :name="'site_items[' + index + '][unit]'"
                                                        class="form-control" x-model="item.unit" required>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label class="form-label">Informasi</label>
                                                    <input type="text" :name="'site_items[' + index + '][information]'"
                                                        class="form-control" x-model="item.information">
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label class="form-label">Dokumen (opsional)</label>
                                                    <input type="file"
                                                        :name="'site_items[' + index + '][document_file][]'"
                                                        class="form-control" multiple>
                                                </div>
                                                <div class="col-12 text-end mt-2">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        x-on:click="removeItem(index)"><i class="fas fa-trash"></i>
                                                        Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Workshop Items Form -->
                            <template x-if="category === 'workshop'">
                                <div class="workshop-items">
                                    <template x-for="(item, index) in workshopItems" :key="index">
                                        <div class="border rounded p-3 mb-3">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label required">Keterangan Item</label>
                                                    <select :name="'workshop_items[' + index + '][explanation_items]'"
                                                        class="form-select" x-model="item.explanation_items" required
                                                        @change="onItemChange(index, 'workshop')">
                                                        <option value="">Pilih Item</option>
                                                        <template x-for="taskItem in taskItems" :key="taskItem.id">
                                                            <option :value="taskItem.nama_barang"
                                                                x-text="taskItem.nama_barang"
                                                                :disabled="workshopItems.some((i, idx) => i.explanation_items ===
                                                                    taskItem.nama_barang && idx !== index)">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Jumlah</label>
                                                    <input type="number"
                                                        :name="'workshop_items[' + index + '][quantity]'"
                                                        class="form-control" x-model="item.quantity" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Satuan</label>
                                                    <input type="text" :name="'workshop_items[' + index + '][unit]'"
                                                        class="form-control" x-model="item.unit" required>
                                                </div>
                                                <div class="col-12 text-end mt-2">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        x-on:click="removeWorkshopItem(index)"><i
                                                            class="fas fa-trash"></i> Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Summary Card -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Ringkasan</h5>
                            <dl>
                                <dt>Total Item</dt>
                                <dd x-text="getTotalItems()"></dd>
                            </dl>
                            <hr>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Simpan SPB
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            .form-label.required::after {
                content: "*";
                color: red;
                margin-left: 4px;
            }
        </style>
    @endpush

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('spbForm', () => ({
                    category: @json(old('category_entry', 'site')),
                    tasks: @json($tasks ?? []),
                    selectedTask: @json(old('task_id', $task?->id)),
                    siteItems: @json(old('site_items', $siteItemsDefault)),
                    workshopItems: @json(old('workshop_items', $workshopItemsDefault)),
                    taskItems: [],
                    init() {
                        if (this.selectedTask) {
                            this.getTaskItems(this.selectedTask);
                        }
                        this.$watch('selectedTask', value => {
                            this.getTaskItems(value);
                        });
                    },

                    async getProjectTasks(projectId) {
                        if (!projectId) {
                            this.tasks = [];
                            this.selectedTask = '';
                            return;
                        }

                        try {
                            const response = await fetch(`/api/projects/${projectId}/tasks`);
                            const data = await response.json();
                            this.tasks = data;

                            // Jika tidak ada task yang dipilih, reset selection
                            if (!this.tasks.find(t => t.id == this.selectedTask)) {
                                this.selectedTask = '';
                            }
                        } catch (error) {
                            console.error('Error fetching tasks:', error);
                            this.tasks = [];
                            this.selectedTask = '';
                        }
                    },

                    getTaskItems(taskId) {
                        if (!taskId) {
                            this.taskItems = [];
                            return;
                        }
                        fetch(`/api/tasks/${taskId}/items`)
                            .then(res => res.json())
                            .then(data => {
                                this.taskItems = data.items || [];
                            });
                    },

                    addItem() {
                        this.siteItems.push({
                            item_name: '',
                            quantity: '',
                            unit: '',
                            information: ''
                        });
                    },

                    removeItem(index) {
                        this.siteItems.splice(index, 1);
                    },

                    addWorkshopItem() {
                        this.workshopItems.push({
                            explanation_items: '',
                            quantity: '',
                            unit: ''
                        });
                    },

                    removeWorkshopItem(index) {
                        this.workshopItems.splice(index, 1);
                    },

                    onItemChange(index, type) {
                        let selected, itemsArr;
                        if (type === 'site') {
                            selected = this.siteItems[index].item_name;
                            itemsArr = this.siteItems;
                        } else {
                            selected = this.workshopItems[index].explanation_items;
                            itemsArr = this.workshopItems;
                        }
                        const found = this.taskItems.find(i => i.nama_barang === selected);
                        if (found) {
                            itemsArr[index].quantity = found.jumlah;
                            itemsArr[index].unit = found.satuan;
                        } else {
                            itemsArr[index].quantity = '';
                            itemsArr[index].unit = '';
                        }
                    },

                    getTotalItems() {
                        return this.category === 'site' ?
                            this.siteItems.length :
                            this.workshopItems.length;
                    }
                }));
            });
        </script>
    @endpush
@endsection
