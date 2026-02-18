<div>
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="text-white mb-2">📋 تتبع الفواتير</h2>
                            <p class="text-white-50 mb-0">عرض وإدارة جميع الفواتير والمنتجات المرتبطة بها</p>
                        </div>
                        {{-- <div class="d-flex gap-2">
                            <span class="badge bg-white text-primary p-3 fs-6">
                                <i class="bi bi-file-text me-2"></i>
                                إجمالي الفواتير: {{ $trucks->total() }}
                            </span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="search-box position-relative">
                                <i
                                    class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control form-control-lg ps-5" placeholder=" بحث  ..."
                                    wire:model.live.debounce.300ms="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-lg" wire:model.live="perPage">
                                <option value="5">5 تراكات لكل صفحة</option>
                                <option value="10">10 تراكات لكل صفحة</option>
                                <option value="25">25 تراكات لكل صفحة</option>
                                <option value="50">50 تراكات لكل صفحة</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-outline-primary w-100" wire:click="$refresh">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                تحديث
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trucks Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">رقم التراك</th>
                                    <th class="py-3">إجمالي سعر الشراء</th>
                                    <th class="py-3">عدد الفواتير</th>
                                    <th class="py-3 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trucks as $truck)
                                    <tr>
                                        <td class="px-4">
                                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                                {{ $truck->number_track }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                {{ number_format($truck->totalbuyprice ?? 0) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $truck->invoice_count ?? 0 }}</span>
                                        </td>
                                        <td class="text-center">

                                            <button class="btn btn-sm btn-outline-primary"
                                                wire:click="ordered('{{ $truck->id }}')">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-primary"
                                                wire:click="viewTruckInvoices('{{ $truck->number_track }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>


                                            <button class="btn btn-sm btn-outline-danger"
                                                wire:click="confirmDeleteTruck({{ $truck->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>

                                    <!-- Display truck invoices when selected -->
                                    @if ($selectedTruck === $truck->number_track)
                                        <tr class="bg-light">
                                            <td colspan="5">
                                                <div class="p-3">
                                                    <h6>فواتير التراك رقم #{{ $truck->number_track }}</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>رقم الفاتورة</th>
                                                                <th>اسم العميل</th>
                                                                <th>رقم الهاتف</th>
                                                                <th>تاريخ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($truckInvoices as $invoice)
                                                                <tr>
                                                                    <td>{{ $invoice->invoice_number }}</td>
                                                                    <td>{{ $invoice->name }}</td>
                                                                    <td>{{ $invoice->phone }}</td>
                                                                    <td>{{ $invoice->today_date->format('Y-m-d') }}
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">لا توجد
                                                                        فواتير لهذا التراك</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                        wire:click="closeTruckInvoices">إغلاق</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لا توجد تراكات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($trucks->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                عرض {{ $trucks->firstItem() }} - {{ $trucks->lastItem() }} من أصل
                                {{ $trucks->total() }} تراكات
                            </div>
                            <div>
                                {{ $trucks->links('livewire::bootstrap') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('livewire:init', () => {

            // ===============================
            // Confirm Delete
            // ===============================
            Livewire.on('confirm-delete-truck', (event) => {

                Swal.fire({
                    title: "تأكيد الحذف",
                    text: "سيتم حذف التراك وتحويل الفواتير إلى Pending",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "نعم احذف",
                    cancelButtonText: "إلغاء"
                }).then((result) => {

                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteTruckConfirmed', {
                            id: event.id
                        });
                    }

                });

            });


            // ===============================
            // Success Alert
            // ===============================
            Livewire.on('swal', (event) => {

                Swal.fire({
                    title: event.title,
                    icon: event.icon,
                    timer: 2000,
                    showConfirmButton: false
                });

            });

        });
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .search-box input {
            padding-right: 2.5rem;
        }

        .search-box i {
            left: 1rem !important;
            right: auto !important;
        }

        .invoice-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge {
            font-weight: 500;
        }

        /* RTL adjustments */
    </style>
</div>
