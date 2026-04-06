<div class="sold-trucks-page">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm sold-header-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="sold-header-title mb-2">🚚 التراكات المباعة</h2>
                            <p class="sold-header-subtitle mb-0">
                                عرض وإدارة جميع التراكات التي تم بيع فواتيرها
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <span class="badge sold-total-badge p-3 fs-6">
                                <i class="bi bi-truck me-2"></i>
                                إجمالي التراكات المباعة: {{ $trucks->total() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm sold-surface-card">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="sold-search-box position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 sold-search-icon"></i>
                                <input
                                    type="text"
                                    class="form-control form-control-lg ps-5 sold-theme-input"
                                    placeholder=" بحث  ..."
                                    wire:model.live.debounce.300ms="search"
                                >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select class="form-select form-select-lg sold-theme-input" wire:model.live="perPage">
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
            <div class="card border-0 shadow-sm sold-surface-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 sold-main-table">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4">رقم التراك</th>
                                    <th class="py-3">إجمالي سعر الشراء</th>
                                    <th class="py-3">عدد الفواتير</th>
                                    <th class="py-3 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trucks as $truck)
                                    <tr class="sold-table-row">
                                        <td class="px-4">
                                            <span class="badge sold-truck-number-badge p-2">
                                                {{ $truck->number_track }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-bold sold-price-text">
                                                {{ number_format($truck->totalbuyprice ?? 0) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge sold-count-badge">
                                                {{ $truck->invoice_count ?? 0 }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <button
                                                class="btn btn-sm btn-outline-primary me-1"
                                                wire:click="ordered('{{ $truck->id }}')"
                                                title="إرجاع"
                                            >
                                                <i class="fas fa-undo sold-undo-icon"></i>
                                            </button>

                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                wire:click="viewTruckInvoices('{{ $truck->number_track }}')"
                                                title="عرض الفواتير"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    @if ($selectedTruck === $truck->number_track)
                                        <tr class="sold-expanded-row">
                                            <td colspan="5">
                                                <div class="p-3 sold-expanded-box">
                                                    <h6 class="sold-expanded-title mb-3">
                                                        فواتير التراك رقم #{{ $truck->number_track }}
                                                    </h6>

                                                    <table class="table table-sm table-bordered mb-3 sold-inner-table">
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
                                                                    <td>{{ $invoice->today_date->format('Y-m-d') }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center sold-empty-text">
                                                                        لا توجد فواتير لهذا التراك
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>

                                                    <button
                                                        class="btn btn-sm btn-outline-secondary"
                                                        wire:click="closeTruckInvoices"
                                                    >
                                                        إغلاق
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center sold-empty-text py-4">
                                            لا توجد تراكات
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($trucks->hasPages())
                    <div class="card-footer border-0 py-3 sold-footer">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="sold-footer-text">
                                عرض {{ $trucks->firstItem() }} - {{ $trucks->lastItem() }} من أصل
                                {{ $trucks->total() }} تراكات
                            </div>

                            <div class="sold-pagination">
                                {{ $trucks->links('livewire::bootstrap') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .sold-trucks-page .sold-header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            border-radius: 20px;
        }

        .sold-trucks-page .sold-header-title {
            color: #ffffff;
            font-weight: 800;
        }

        .sold-trucks-page .sold-header-subtitle {
            color: rgba(255, 255, 255, 0.75);
        }

        .sold-trucks-page .sold-total-badge {
            background: rgba(255, 255, 255, 0.95);
            color: #2563eb;
            font-weight: 700;
            border-radius: 16px;
        }

        .sold-trucks-page .sold-surface-card {
            background: var(--bg-card);
            color: var(--text);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
            border-radius: 18px;
        }

        .sold-trucks-page .sold-surface-card .card-body,
        .sold-trucks-page .sold-surface-card .card-footer {
            background: transparent;
            color: inherit;
        }

        .sold-trucks-page .sold-search-box input {
            padding-right: 2.5rem;
        }

        .sold-trucks-page .sold-search-icon {
            left: 1rem !important;
            right: auto !important;
            color: var(--text-secondary);
        }

        .sold-trucks-page .sold-theme-input {
            background: var(--bg);
            color: var(--text);
            border-color: var(--border);
        }

        .sold-trucks-page .sold-theme-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.9;
        }

        .sold-trucks-page .sold-theme-input:focus {
            background: var(--bg);
            color: var(--text);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 107, 90, 0.12);
        }

        .sold-trucks-page .sold-main-table {
            margin-bottom: 0 !important;
            color: var(--text);
        }

        .sold-trucks-page .sold-main-table thead th {
            background: var(--bg);
            color: var(--text);
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .sold-trucks-page .sold-main-table tbody td {
            background: var(--bg-card);
            color: var(--text);
            border-bottom: 1px solid var(--border);
            box-shadow: none;
        }

        .sold-trucks-page .sold-table-row:hover td {
            background: var(--bg);
        }

        .sold-trucks-page .sold-truck-number-badge {
            background: rgba(59, 130, 246, 0.14);
            color: #2563eb;
            font-weight: 700;
            border-radius: 12px;
        }

        .sold-trucks-page .sold-price-text {
            color: #16a34a;
        }

        .sold-trucks-page .sold-count-badge {
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
        }

        .sold-trucks-page .sold-undo-icon {
            color: red;
        }

        .sold-trucks-page .sold-expanded-row td {
            background: var(--bg);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .sold-trucks-page .sold-expanded-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .sold-trucks-page .sold-expanded-title {
            color: var(--text);
            font-weight: 700;
        }

        .sold-trucks-page .sold-inner-table {
            margin-bottom: 0;
            color: var(--text);
        }

        .sold-trucks-page .sold-inner-table thead th {
            background: var(--bg);
            color: var(--text);
            border-color: var(--border);
        }

        .sold-trucks-page .sold-inner-table tbody td {
            background: var(--bg-card);
            color: var(--text);
            border-color: var(--border);
        }

        .sold-trucks-page .sold-inner-table tbody tr:hover td {
            background: var(--bg);
        }

        .sold-trucks-page .sold-empty-text {
            color: var(--text-secondary);
        }

        .sold-trucks-page .sold-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border) !important;
        }

        .sold-trucks-page .sold-footer-text {
            color: var(--text-secondary);
        }

        .sold-trucks-page .sold-pagination .pagination {
            margin-bottom: 0;
        }

        .sold-trucks-page .sold-pagination .page-link {
            background: var(--bg);
            color: var(--text);
            border-color: var(--border);
            box-shadow: none;
        }

        .sold-trucks-page .sold-pagination .page-link:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .sold-trucks-page .sold-pagination .page-item.active .page-link {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .sold-trucks-page .sold-pagination .page-item.disabled .page-link {
            background: var(--bg-card);
            color: var(--text-secondary);
            border-color: var(--border);
        }

        @media (max-width: 768px) {
            .sold-trucks-page .sold-total-badge {
                width: 100%;
                text-align: center;
            }

            .sold-trucks-page .sold-main-table thead th,
            .sold-trucks-page .sold-main-table tbody td {
                white-space: nowrap;
            }
        }
    </style>
</div>