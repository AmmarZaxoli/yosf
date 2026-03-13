<div>
    <!-- Header Section with Search and Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-truck text-white me-2"></i>
                    إزالة التراك من الفاتورة او تحويل
                </h4>
                <span class="badge bg-primary p-2">
                    إجمالي الفواتير: {{ $invoices->total() }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter Row -->
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0"
                            placeholder="بحث برقم الفاتورة, اسم العميل, رقم الهاتف, العنوان, رقم التراك..."
                            wire:model.live.debounce.300ms="search">
                        @if ($search)
                            <button class="btn btn-outline-secondary" type="button" wire:click="$set('search', '')">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-calendar text-success"></i>
                        </span>
                        <input type="date" class="form-control border-start-0" wire:model.live="dateFrom"
                            placeholder="من تاريخ">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-calendar text-danger"></i>
                        </span>
                        <input type="date" class="form-control border-start-0" wire:model.live="dateTo"
                            placeholder="إلى تاريخ">
                    </div>
                </div>

                <div class="col-md-1">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if ($search || $dateFrom || $dateTo)
                <div class="mt-3 d-flex align-items-center">
                    <span class="text-muted me-2">الفلاتر النشطة:</span>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($search)
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                <i class="fas fa-search me-1"></i>بحث: {{ $search }}
                                <button type="button" class="btn-close btn-close-primary ms-2" style="font-size: 10px;"
                                    wire:click="$set('search', '')"></button>
                            </span>
                        @endif

                        @if ($dateFrom)
                            <span class="badge bg-success bg-opacity-10 text-success p-2">
                                <i class="fas fa-calendar me-1"></i>من: {{ $dateFrom }}
                                <button type="button" class="btn-close btn-close-success ms-2" style="font-size: 10px;"
                                    wire:click="$set('dateFrom', '')"></button>
                            </span>
                        @endif

                        @if ($dateTo)
                            <span class="badge bg-danger bg-opacity-10 text-danger p-2">
                                <i class="fas fa-calendar me-1"></i>إلى: {{ $dateTo }}
                                <button type="button" class="btn-close btn-close-danger ms-2" style="font-size: 10px;"
                                    wire:click="$set('dateTo', '')"></button>
                            </span>
                        @endif

                        <button class="btn btn-sm btn-link text-danger" wire:click="clearFilters">
                            <i class="fas fa-times-circle me-1"></i>مسح الكل
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th width="5%" class="text-center">
                                <i class="fas fa-file-invoice text-primary me-1"></i>
                                رقم الفاتورة
                            </th>
                            <th width="7%" class="text-center">
                                <i class="fas fa-user text-muted me-1"></i>
                                العميل
                            </th>
                            <th width="12%" class="text-center">
                                <i class="fas fa-phone text-muted me-1"></i>
                                رقم الهاتف
                            </th>
                            <th width="15%">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                العنوان
                            </th>
                            <th width="7%" class="text-center">
                                <i class="fas fa-truck text-info me-1"></i>
                                التراك
                            </th>
                            <th width="7%" class="text-center">
                                <i class="fas fa-calendar text-success me-1"></i>
                                التاريخ
                            </th>
                            <th width="8%" class="text-center">
                                <i class="fas fa-box text-info me-1"></i>
                                المنتجات
                            </th>
                            <th width="6%" class="text-center">
                                <i class="fas fa-cubes text-success me-1"></i>
                                الكمية
                            </th>
                            <th width="12%" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $index => $invoice)
                            <tr>
                                <td class="text-center fw-bold">
                                    {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 invoice-number">
                                        {{ $invoice->invoice_number }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">

                                        <span class="fw-medium">{{ $invoice->name }}</span>
                                    </div>
                                </td>

                                <td class="text-center" dir="ltr">
                                    <span class="badge bg-light text-dark">

                                        {{ $invoice->phone }}
                                    </span>
                                </td>

                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                        title="{{ $invoice->address }}">

                                        {{ $invoice->address ?: '—' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if ($invoice->id_truck && $invoice->id_truck !== 'Peading')
                                        <span class="badge bg-info bg-opacity-10 text-white">

                                            {{ $invoice->id_truck }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            قيد الانتظار
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="text-muted">

                                        {{ $invoice->today_date->format('Y-m-d') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-info text-white badge-count">
                                        <i class="fas fa-file-invoice text-white me-1"></i>
                                        {{ $invoice->items_count }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-success text-white badge-count">
                                        <i class="fas fa-cubes me-1"></i>
                                        {{ $invoice->total_quantity }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                            wire:click="$dispatch('confirm-clear-truck', { id: {{ $invoice->id }} })">
                                            مسح التراك
                                        </button>

                                        <button class="btn btn-sm btn-outline-warning"
                                            wire:click="prepareTransfer({{ $invoice->id }})">
                                            نقل
                                        </button>
                                    </div>



                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice fa-4x mb-3 opacity-25"></i>
                                        <p class="mb-2 fs-5 fw-bold">لا توجد فواتير لعرضها</p>
                                        <p class="mb-0 text-secondary">
                                            @if ($search || $dateFrom || $dateTo)
                                                لا توجد نتائج تطابق معايير البحث
                                            @else
                                                قم بإنشاء فاتورة جديدة للبدء
                                            @endif
                                        </p>
                                        @if ($search || $dateFrom || $dateTo)
                                            <button class="btn btn-primary mt-3" wire:click="clearFilters">
                                                <i class="fas fa-times-circle me-2"></i>
                                                مسح الفلاتر
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination and Info -->
        @if ($invoices->hasPages() || $invoices->total() > 0)
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }}
                        من إجمالي {{ $invoices->total() }} فاتورة
                    </div>
                    <div>
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>


    <!-- Modal -->

    <div class="modal fade" id="transferTruckModal" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-header ">
                    <h6 class="modal-title fw-bold">
                        <i class="bi bi-arrow-left-right me-1 text-warning"></i> تحويل الفاتورة
                    </h6>
                    <button type="button" class="btn-close" style="font-size: 0.7rem;"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-2">
                        <label class="small fw-bold text-muted mb-1">اختر التراك الجديد : </label>
                        <select class="form-select form-select-sm shadow-none" wire:model="newTruckNumber"
                            style="border-radius: 8px;">
                            <option value="">-- اختار--</option>
                            @foreach ($allTrucks as $truck)
                                <option value="{{ $truck->number_track }}"> {{ $truck->number_track }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light py-1 border-0 d-flex ">


                    <button type="button" wire:click="transferTruck" wire:loading.attr="disabled"
                        class="btn btn-primary">
                        <span wire:loading.remove>حفظ الفاتورة</span>
                        <span wire:loading>جار الحفظ...</span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>

                </div>
            </div>
        </div>
    </div>






    <script>
        document.addEventListener('livewire:init', () => {

            $wire.on("confirm-delete-truck", (event) => {
                Swal.fire({
                    title: "تأكيد الحذف",
                    text: "هل أنت متأكد من حذف التراك؟ سيتم تحويل الفواتير إلى Pending",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "نعم، احذف!",
                    cancelButtonText: "إلغاء",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("deleteTruckConfirmed", {
                            id: event.id
                        });
                    }
                });
            });

        });


        // ڤەکرنا مۆداڵێ
        window.addEventListener('open-transfer-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('transferTruckModal'));
            myModal.show();
        });

        // گرتنا مۆداڵێ پشتی تمامبوونێ
        window.addEventListener('close-modal-transfer', event => {
            var myModalEl = document.getElementById('transferTruckModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>


    <script>
        document.addEventListener('livewire:init', () => {
            // Initialize tooltips
            let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

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
