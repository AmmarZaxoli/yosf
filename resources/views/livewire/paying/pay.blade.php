<div>
    <div class="container-fluid py-4">
        <div class="text-center my-4">
            <div class="header-container d-inline-block">
                <h3 class="header-title mb-1">الدفعات</h3>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="filters-card">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="search-box-modern">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-input-modern"
                            placeholder="بحث برقم الفاتورة، الهاتف، أو رقم التراك..."
                            wire:model.live.debounce.300ms="search">
                    </div>
                </div>

                <!-- Filter by Driver -->
                <div class="col-md-3">
                    <select class="form-input-modern" wire:model.live="selectedDriverId">
                        <option value="">جميع السائقين</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-md-2">
                    <input type="date" class="form-input-modern" wire:model.live="dateFrom" placeholder="من تاريخ">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-input-modern" wire:model.live="dateTo" placeholder="إلى تاريخ">
                </div>

                <!-- Reset Button -->
                <div class="col-md-1">
                    <button class="btn btn-secondary w-100" style="border-radius: 16px; height: 48px;"
                        wire:click="resetFilters" title="إعادة تعيين">
                        <i class="fas fa-redo-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-icon bg-primary">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">إجمالي الفواتير</span>
                        <span class="summary-value">{{ $totalInvoices }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">غير مدفوعة</span>
                        <span class="summary-value">{{ $unpaidCount }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">مدفوعة</span>
                        <span class="summary-value">0</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-icon bg-info">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">إجمالي المبالغ</span>
                        <span class="summary-value">{{ number_format($totalAmount, 2) }} ₪</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-bar mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="selected-info">
                    @if(count($selectedInvoices) > 0)
                        <span class="badge-count-modern">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            تم تحديد {{ count($selectedInvoices) }} فاتورة
                        </span>
                    @else
                        <span class="text-muted">لم يتم تحديد أي فواتير</span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <!-- Change Delivery Date Button -->
                    <button class="btn btn-warning px-4" style="border-radius: 16px; background: linear-gradient(135deg, #f97316, #fbbf24); border: none; color: white;" 
                        wire:click="openChangeDateModal" @disabled(count($selectedInvoices) === 0)>
                        <i class="fas fa-calendar-alt me-2"></i>
                        تغيير تاريخ التوصيل
                        @if(count($selectedInvoices) > 0)
                            <span class="badge bg-white text-warning ms-2">{{ count($selectedInvoices) }}</span>
                        @endif
                    </button>

                    <!-- Change Driver Button -->
                    <button class="btn btn-primary px-4" style="border-radius: 16px;" wire:click="openBulkDriverModal"
                        @disabled(count($selectedInvoices) === 0)>
                        <i class="fas fa-user-tie me-2"></i>
                        تغيير السائق
                        @if(count($selectedInvoices) > 0)
                            <span class="badge bg-white text-primary ms-2">{{ count($selectedInvoices) }}</span>
                        @endif
                    </button>

                    <button class="btn btn-success px-4" style="border-radius: 16px;" wire:click="confirmPayment"
                        @disabled(count($selectedInvoices) === 0)>
                        <i class="fas fa-credit-card me-2"></i>
                        تأكيد الدفع
                        @if(count($selectedInvoices) > 0)
                            <span class="badge bg-white text-success ms-2">{{ count($selectedInvoices) }}</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <h5 class="header-title">قائمة الفواتير</h5>
                        <p class="header-subtitle">الفواتير الغير مدفوعة</p>
                    </div>
                </div>
                <span class="badge-count-modern">{{ $invoices->total() }} فاتورة</span>
            </div>

            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th width="3%">
                                <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                            </th>
                            <th width="5%">#</th>
                            <th width="10%">رقم الفاتورة</th>
                            <th width="12%">العميل</th>
                            <th width="10%">رقم الهاتف</th>
                            <th width="10%">السائق</th>
                            <th width="8%">رقم التراك</th>
                            <th width="8%">تاريخ التوصيل</th>
                            <th width="10%">المبلغ</th>
                            <th width="8%">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $index => $invoice)
                            <tr class="{{ $invoice->is_active ? 'table-success' : '' }}">
                                <td class="text-center">
                                    <input type="checkbox" wire:model.live="selectedInvoices" value="{{ $invoice->id }}"
                                        class="form-check-input" {{ $invoice->is_active ? 'disabled' : '' }}>
                                </td>
                                <td class="text-center">
                                    <span class="row-number">{{ $invoices->firstItem() + $index }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="invoice-badge">#{{ $invoice->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="cell-with-icon">
                                        <i class="fas fa-user-circle" style="color: #3b82f6;"></i>
                                        {{ $invoice->customer->name ?? '—' }}
                                    </div>
                                </td>
                                <td class="text-center" dir="ltr">
                                    <span class="phone-number">{{ $invoice->customer->phone ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($invoice->customer && $invoice->customer->driver)
                                        <span class="driver-badge">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $invoice->customer->driver->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="truck-badge">{{ $invoice->id_truck ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="date-badge">
                                        @if($invoice->customer && $invoice->customer->delivery_date)
                                            {{ $invoice->customer->delivery_date instanceof \Carbon\Carbon
                                                ? $invoice->customer->delivery_date->format('Y-m-d')
                                                : \Carbon\Carbon::parse($invoice->customer->delivery_date)->format('Y-m-d') }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="amount-badge">
                                        {{ number_format($invoice->total_price ?? 0, 2) }} ₪
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($invoice->is_active)
                                        <span class="status-badge status-paid">
                                            <i class="fas fa-check-circle me-1"></i> مدفوع
                                        </span>
                                    @else
                                        <span class="status-badge status-unpaid">
                                            <i class="fas fa-clock me-1"></i> غير مدفوع
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">
                                    <div class="empty-state-content">
                                        <i class="fas fa-file-invoice fa-4x"></i>
                                        <h5>لا توجد فواتير</h5>
                                        <p>لا توجد فواتير للعرض</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }}
                        من {{ $invoices->total() }} فاتورة
                    </div>
                    <div class="pagination-links">
                        {{ $invoices->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Bulk Driver Modal --}}
        @if($showBulkDriverModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-white" style="background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                            <h5 class="modal-title">
                                <i class="fas fa-user-tie me-2"></i>
                                تغيير السائق للفواتير المحددة
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeBulkDriverModal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اختر السائق الجديد</label>
                                <select class="form-control" wire:model="bulkDriverId">
                                    <option value="">-- اختر السائق --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                @error('bulkDriverId') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                عدد الفواتير المحددة: <strong>{{ count($selectedInvoices) }}</strong>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" wire:click="closeBulkDriverModal">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="updateBulkDriver">
                                <i class="fas fa-save me-2"></i> تحديث
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Change Delivery Date Modal --}}
        @if($showChangeDateModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-white" style="background: linear-gradient(135deg, #f97316, #fbbf24);">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-alt me-2"></i>
                                تغيير تاريخ التوصيل
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeChangeDateModal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ التوصيل الجديد</label>
                                <input type="date" class="form-control" wire:model="newDeliveryDate"
                                    style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; width: 100%;">
                                @error('newDeliveryDate') 
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="alert alert-info" style="background: #e0f2fe; border: none; border-radius: 12px;">
                                <i class="fas fa-info-circle me-2"></i>
                                عدد الفواتير المحددة: <strong>{{ count($selectedInvoices) }}</strong>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" wire:click="closeChangeDateModal"
                                style="border-radius: 12px; padding: 10px 24px;">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="confirmChangeDate"
                                style="border-radius: 12px; padding: 10px 24px; background: linear-gradient(135deg, #f97316, #fbbf24); border: none;">
                                <i class="fas fa-save me-2"></i> تحديث
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', function () {
            console.log('Livewire initialized');
            
            // Payment confirmation
            Livewire.on('showPaymentConfirmation', function () {
                Swal.fire({
                    title: 'تأكيد الدفع',
                    text: 'هل أنت متأكد من تأكيد دفع الفواتير المحددة؟',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، تأكيد الدفع',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('processPayment');
                    }
                });
            });

            // Date change confirmation
            Livewire.on('confirmDateChange', function () {
                Swal.fire({
                    title: 'تأكيد تغيير التاريخ',
                    text: 'هل أنت متأكد من تغيير تاريخ التوصيل للفواتير المحددة؟',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، تغيير التاريخ',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('processChangeDate');
                    }
                });
            });

            // Alert handler
            Livewire.on('showAlert', function (event) {
                if (event.type === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: event.message || '✅',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else if (event.type === 'warning') {
                    Swal.fire({
                        icon: 'warning',
                        title: event.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        });
    </script>

    <style>
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .1);
            border: 1px solid #f1f5f9;
        }

        .summary-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .summary-icon.bg-primary {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }

        .summary-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .summary-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .summary-icon.bg-info {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
        }

        .summary-details {
            flex: 1;
        }

        .summary-label {
            display: block;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .summary-value {
            display: block;
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
        }

        /* Action Bar */
        .action-bar {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 5px 20px -5px rgba(0, 0, 0, .1);
            border: 1px solid #f1f5f9;
        }

        .selected-info {
            font-size: 15px;
            font-weight: 500;
        }

        /* Driver Badge */
        .driver-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        /* Amount Badge */
        .amount-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }

        /* Status Badge */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Table Row for Paid Invoices */
        .table-modern tr.table-success {
            background-color: rgba(16, 185, 129, 0.05);
        }

        .table-modern tr.table-success:hover td {
            background-color: rgba(16, 185, 129, 0.1);
        }

        /* Disabled Checkbox */
        .form-check-input:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* Filters */
        .filters-card {
            background: white;
            border-radius: 24px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .1);
            border: 1px solid #f1f5f9;
        }

        .search-box-modern {
            position: relative;
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 1;
        }

        .form-input-modern {
            width: 100%;
            padding: 14px 45px 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            font-family: 'Tajawal', sans-serif;
            transition: all .2s;
            background: white;
        }

        .form-input-modern:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .1);
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .1);
            border: 1px solid #f1f5f9;
        }

        .table-card-header {
            padding: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern th {
            background: #f8fafc;
            padding: 16px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-modern td {
            padding: 16px;
            color: #475569;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
        }

        .table-modern tr:hover td {
            background: #f8fafc;
        }

        .invoice-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .row-number {
            font-weight: 600;
            color: #3b82f6;
        }

        .cell-with-icon {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .phone-number {
            font-family: monospace;
            direction: ltr;
            display: inline-block;
        }

        .truck-badge {
            background: #f1f5f9;
            color: #64748b;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        .date-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        .count-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .count-badge.info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .count-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-count-modern {
            background: #f1f5f9;
            color: #3b82f6;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .pagination-wrapper {
            padding: 1.5rem;
            border-top: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pagination-info {
            color: #64748b;
            font-size: 14px;
        }

        .pagination-links .pagination {
            margin: 0;
            gap: 5px;
        }

        .pagination-links .page-link {
            border: none;
            border-radius: 12px;
            color: #64748b;
            padding: 8px 14px;
            font-weight: 500;
        }

        .pagination-links .page-item.active .page-link {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
        }

        /* Modal Styles */
        .modal-backdrop {
            opacity: 0.5 !important;
        }

        .modal.show {
            display: block;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .empty-state {
            text-align: center;
            padding: 4rem !important;
        }

        .empty-state-content {
            color: #94a3b8;
        }

        .empty-state-content i {
            font-size: 48px;
            margin-bottom: 1rem;
            opacity: .5;
        }

        .empty-state-content h5 {
            color: #334155;
            margin-bottom: .5rem;
        }

        .empty-state-content p {
            color: #64748b;
            margin: 0;
        }
    </style>
</div>