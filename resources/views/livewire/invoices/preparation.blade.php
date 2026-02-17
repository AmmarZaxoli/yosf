<div>
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="3%">#</th>
                       
                        <th width="8%" class="text-center">رقم الفاتورة</th>
                        <th width="10%" class="text-center">
                            <i class="fas fa-user text-muted me-1"></i>العميل
                        </th>
                        <th width="12%" class="text-center">
                            <i class="fas fa-phone text-muted me-1"></i>رقم الهاتف
                        </th>
                        <th width="15%">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>العنوان
                        </th>
                        <th width="8%" class="text-center">
                            <i class="fas fa-truck text-info me-1"></i>التراك
                        </th>
                        <th width="9%">
                            <i class="fas fa-calendar text-success me-1"></i>التاريخ
                        </th>
                        <th width="8%" class="text-center">
                            <i class="fas fa-box text-info me-1"></i>المنتجات
                        </th>
                        <th width="8%" class="text-center">
                            <i class="fas fa-cubes text-success me-1"></i>الكمية
                        </th>
                        <th width="12%" class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $index => $invoice)
                        <tr>
                            <td class="text-center">
                                {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}
                            </td>
                          
                            <td class="text-center">
                                <span class="badge bg-primary invoice-number">
                                    #{{ $invoice->invoice_number }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-secondary me-2"></i>
                                    {{ $invoice->name }}
                                </div>
                            </td>
                            <td class="text-center" dir="ltr">
                                {{ $invoice->phone }}
                            </td>
                            <td>
                                <span title="{{ $invoice->address }}">
                                    {{ Str::limit($invoice->address, 25) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $invoice->id_truck ?? '—' }}
                            </td>
                            <td class="text-center">
                                {{ $invoice->today_date->format('Y-m-d') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info badge-count">
                                    {{ $invoice->items_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success badge-count">
                                    {{ $invoice->total_quantity }}
                                </span>
                            </td>
                            <td>

                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-light text-primary"
                                        wire:click="openEditModal({{ $invoice->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                    <p class="mb-0 fs-5">لا توجد فواتير لعرضها</p>
                                    <p class="mb-0 text-secondary">قم بإنشاء فاتورة جديدة للبدء</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($invoices->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }}
                    من {{ $invoices->total() }} فاتورة
                </div>
                <div>
                    {{ $invoices->links() }}
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="staticBackdrop" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        تعديل الفاتورة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">رقم التراك</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-truck"></i>
                            </span>

                            <input type="text" class="form-control @error('id_truck') is-invalid @enderror"
                                wire:model="id_truck">
                        </div>

                        @error('id_truck')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        إلغاء
                    </button>

                    <button class="btn btn-primary" wire:click="updateInvoice">
                        حفظ
                    </button>
                </div>

            </div>
        </div>
    </div>


    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('show-edit-modal', () => {
                let modal = new bootstrap.Modal(
                    document.getElementById('staticBackdrop')
                );
                modal.show();
            });

            Livewire.on('close-modal', () => {
                let modalEl = document.getElementById('staticBackdrop');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });

        });
    </script>

</div>
