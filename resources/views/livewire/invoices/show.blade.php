<div class="invoices-container">
    <style>
        .fl-wrapper {
            left: .75em !important;
            right: auto !important;
        }

        .fl-wrapper[data-position] {
            top: .75em !important;
        }

        .product-image-modal {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .modal-lg-custom {
            max-width: 800px;
        }

        /* Custom styles for the detail component */
        .detail-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .detail-modal-backdrop.show {
            display: block;
        }

        .detail-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            display: none;
            overflow-y: auto;
        }

        .detail-modal.show {
            display: block;
        }

        /* New layout improvements */
        .invoices-container {
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .stat-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .filters-section {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .filters-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .search-wrapper {
            flex: 2;
            min-width: 300px;
        }

        .date-wrapper {
            flex: 1;
            min-width: 200px;
        }

        .actions-wrapper {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            border-radius: 30px;
            font-weight: 600;
        }

        .invoice-number {
            font-family: monospace;
            font-weight: 600;
        }



        /* Responsive improvements */
        @media (max-width: 768px) {
            .filters-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper,
            .date-wrapper,
            .actions-wrapper {
                width: 100%;
            }

            .actions-wrapper {
                margin-left: 0;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .table-container {
                padding: 0.5rem;
            }

            /* Make table horizontally scrollable on mobile */
            .table-responsive {
                margin: 0 -0.5rem;
            }
        }
    </style>



    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-wrapper">
            <div class="search-wrapper">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="ابحث برقم الفاتورة، اسم العميل، أو الهاتف..."
                        wire:model.live="search">
                </div>
            </div>

            <div class="date-wrapper">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    <input type="date" class="form-control" wire:model.live="dateFilter"
                        placeholder="تاريخ الفاتورة">
                </div>
            </div>

            <div class="actions-wrapper">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                    @if (empty($selectedInvoices)) disabled @endif>
                    <i class="fas fa-truck me-2"></i>
                    تجهيز ({{ count($selectedInvoices) }})
                </button>
            </div>
        </div>
    </div>



    <!-- Invoices Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="3%">#</th>
                        <th width="3%" class="text-center">
                            <input type="checkbox" id="checkAll" wire:model="selectAll">
                        </th>
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
                                <input type="checkbox" wire:model.live="selectedInvoices" value="{{ $invoice->id }}">
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
                                    <button class="btn btn-sm btn-primary text-white"
                                        wire:click="$dispatch('showInvoiceDetail', { invoiceId: {{ $invoice->id }} })"
                                        title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-success text-white"
                                        wire:click="openAddModal({{ $invoice->id }})" title="إضافة إلى تراك">
                                        <i class="fas fa-truck-loading"></i>
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

    <!-- Create Invoice Modal -->


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> إنشاء التراك جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم التراك</label>
                            <input type="text" class="form-control @error('truck_number') is-invalid @enderror"
                                wire:model="truck_number">
                            @error('truck_number')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سعر الشراء</label>
                            <input type="number" class="form-control @error('buyprice') is-invalid @enderror"
                                wire:model="buyprice">
                            @error('buyprice')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" wire:click="TrackInvoice" wire:loading.attr="disabled"
                        class="btn btn-primary">
                        <span wire:loading.remove>حفظ الفاتورة</span>
                        <span wire:loading>جار الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="existingTruckModal" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-truck-moving me-2"></i> إضافة إلى تراك موجود مسبقاً</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اختر التراك</label>
                        <select class="form-select @error('truck_number') is-invalid @enderror"
                            wire:model.live="truck_number">
                            <option value="">-- اختر التراك --</option>
                            @foreach (\App\Models\InfoInvoice::where('status', 0)->latest()->get() as $t)
                                <option value="{{ $t->number_track }}">{{ $t->number_track }}</option>
                            @endforeach
                        </select>
                        @error('truck_number')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($truck_number)
                        <div class="mb-3">
                            <label class="form-label fw-bold">سعر الشراء الحالي (يمكنك تعديله)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" class="form-control" wire:model="existing_truck_price">
                            </div>
                            @error('existing_truck_price')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" wire:click="AddToExistingTruck" class="btn btn-warning">
                        <i class="fas fa-sync-alt me-1"></i> تحديث وإضافة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="existingTruckModal" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> إضافة فاتورة إلى تراك</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اكتب رقم التراك</label>
                        <input type="text" list="trucksList"
                            class="form-control @error('truck_number') is-invalid @enderror"
                            wire:model.live.debounce.500ms="truck_number" placeholder="ادخل رقم التراك...">
                        <datalist id="trucksList">
                            @foreach (\App\Models\InfoInvoice::all() as $t)
                                <option value="{{ $t->number_track }}">
                            @endforeach
                        </datalist>
                        @error('truck_number')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($truck_number)
                        <div class="mb-3">
                            <label class="form-label fw-bold">سعر الشراء الحالي</label>
                            <input type="number" class="form-control" wire:model="existing_truck_price">
                            @error('existing_truck_price')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" wire:click="AddToExistingTruck" class="btn btn-success">تحديث
                        وإضافة</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        window.addEventListener('close-modal', event => {
            var myModalEl = document.getElementById('staticBackdrop');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });

        // Handle select all checkbox
        document.addEventListener('livewire:init', function() {
            const checkAll = document.getElementById('checkAll');
            if (checkAll) {
                checkAll.addEventListener('change', function(e) {
                    @this.set('selectAll', e.target.checked);
                });
            }
        });



        // فەرمانا ڤەکرنا مۆداڵێ
        window.addEventListener('open-existing-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('existingTruckModal'));
            myModal.show();
        });

        // فەرمانا گرتنا مۆداڵێ
        window.addEventListener('close-modal-existing', event => {
            var myModalEl = document.getElementById('existingTruckModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>

    <!-- Include the Detail Component -->
    @livewire('invoices.detail')
</div>
