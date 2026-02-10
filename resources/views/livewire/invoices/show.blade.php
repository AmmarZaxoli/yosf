<div>
    <div class="container-fluid body">
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
        </style>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">عرض الفواتير</h5>
                        
                        <!-- Search Input -->
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 300px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       placeholder="ابحث برقم الفاتورة، اسم العميل، أو الهاتف..."
                                       wire:model.live="search">
                            </div>
                            
                            <!-- Date Filters -->
                            <div class="input-group" style="width: 200px;">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" 
                                       class="form-control" 
                                       wire:model.live="dateFilter"
                                       placeholder="تاريخ الفاتورة">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Success Messages -->
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Invoices Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">رقم الفاتورة</th>
                                        <th width="15%">رقم الهاتف</th>
                                        <th width="20%">العنوان</th>
                                        <th width="10%">رقم الشاحنة</th>
                                        <th width="10%">تاريخ الإنشاء</th>
                                        <th width="10%">عدد المنتجات</th>
                                        <th width="10%">إجمالي الكمية</th>
                                        <th width="10%">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">#{{ $invoice->invoice_number }}</span>
                                            </td>
                                            <td>
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                {{ $invoice->phone }}
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                {{ Str::limit($invoice->address, 30) }}
                                            </td>
                                            <td>
                                                <i class="fas fa-truck text-info me-1"></i>
                                                {{ $invoice->truck_number }}
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar text-success me-1"></i>
                                                {{ $invoice->today_date->format('Y-m-d') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info rounded-pill p-2">
                                                    {{ $invoice->items_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success rounded-pill p-2">
                                                    {{ $invoice->total_quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary"
                                                        wire:click="$dispatch('showInvoiceDetail', { invoiceId: {{ $invoice->id }} })">
                                                    <i class="fas fa-eye me-1"></i> عرض الطلب
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-file-invoice fa-2x mb-3"></i>
                                                    <p class="mb-0">لا توجد فواتير لعرضها</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($invoices->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }} من {{ $invoices->total() }} فاتورة
                                </div>
                                <div>
                                    {{ $invoices->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the Detail Component -->
    @livewire('invoices.detail')
</div>