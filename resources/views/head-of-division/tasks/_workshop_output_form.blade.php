<!-- filepath: d:\Appi\giman\i-harma\resources\views\head-of-division\tasks\_workshop_output_form.blade.php -->
<div class="modal fade" id="workshopOutputModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="workshopOutputForm" action="{{ route('head-of-division.tasks.complete', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">Output Workshop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Masukkan jumlah barang yang telah diproduksi dan tentukan apakah perlu dikirim ke site.
                    </div>

                    @foreach($task->spb->workshopItems as $index => $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">{{ $item->explanation_items }}</h6>

                            <input type="hidden"
                                   name="outputs[{{ $index }}][workshop_spb_id]"
                                   value="{{ $item->id }}">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah Diproduksi</label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control"
                                               name="outputs[{{ $index }}][quantity_produced]"
                                               min="1"
                                               value="{{ $item->quantity }}"
                                               required>
                                        <span class="input-group-text">{{ $item->unit }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Perlu Pengiriman?</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden"
                                               name="outputs[{{ $index }}][need_delivery]"
                                               value="0">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="outputs[{{ $index }}][need_delivery]"
                                               value="1">
                                        <label class="form-check-label">Ya, kirim ke site</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control"
                                          name="outputs[{{ $index }}][notes]"
                                          rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan & Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
