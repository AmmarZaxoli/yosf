<div class="container-fluid py-4">
    <div class="invoice-page-header mb-4 no-print">
        <div class="title-pill">
            <i class="fas fa-print"></i>
            طباعة متعددة للفواتير
        </div>
        <p>اختر الفواتير ثم اضغط على زر الطباعة</p>
    </div>

    <div class="form-card mb-4 no-print">
        <div class="modal-header responsive-header">
            <div class="d-flex align-items-center gap-2 header-main">
                <i class="fas fa-file-invoice fs-5"></i>
                <div>
                    <h5 class="mb-0">الفواتير</h5>
                    <small>حدد فاتورة واحدة أو أكثر للطباعة</small>
                </div>
            </div>

            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap header-actions">
                <span class="badge bg-light text-dark">
                    المحدد: {{ count($selectedInvoices) }}
                </span>

                <button
                    type="button"
                    class="btn btn-primary print-btn"
                    wire:click="openPrint"
                    wire:loading.attr="disabled"
                    @disabled(count($selectedInvoices) === 0)
                >
                    <span wire:loading.remove wire:target="openPrint">
                        <i class="fas fa-print me-2"></i>
                        فتح الطباعة
                    </span>
                    <span wire:loading wire:target="openPrint">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        جاري الفتح...
                    </span>
                </button>
            </div>
        </div>

        <div class="p-4">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-8 col-lg-5">
                    <label class="form-label">
                        <i class="fas fa-search me-1"></i>
                        بحث
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        wire:model.live.debounce.300ms="search"
                        placeholder="رقم الفاتورة أو اسم العميل أو الهاتف أو التراك">
                </div>
            </div>
        </div>
    </div>

    <div class="form-card no-print">
        <div class="orders-table-wrap">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 60px">
                            <input type="checkbox" wire:model.live="selectAll">
                        </th>
                        <th>#</th>
                        <th>رقم الفاتورة</th>
                        <th>اسم العميل</th>
                        <th>الهاتف</th>
                        <th>التراك</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $index => $invoice)
                        <tr>
                            <td data-label="تحديد">
                                <input
                                    type="checkbox"
                                    value="{{ $invoice->id }}"
                                    wire:model.live="selectedInvoices">
                            </td>

                            <td data-label="#">
                                {{ $invoices->firstItem() + $index }}
                            </td>

                            <td data-label="رقم الفاتورة">
                                {{ $invoice->invoice_number ?? '—' }}
                            </td>

                            <td data-label="اسم العميل">
                                {{ $invoice->customer->name ?? '—' }}
                            </td>

                            <td data-label="الهاتف">
                                {{ $invoice->customer->phone ?? '—' }}
                            </td>

                            <td data-label="التراك">
                                {{ $invoice->id_truck ?? '—' }}
                            </td>

                            <td data-label="الإجمالي" class="fw-bold text-primary">
                                {{ number_format($invoice->total_price ?? 0, 0) }}
                            </td>

                            <td data-label="التاريخ">
                                {{ optional($invoice->created_at)->format('Y-m-d') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                لا توجد فواتير
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-3 border-top">
                {{ $invoices->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <style>
        .print-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .responsive-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .orders-table-wrap {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            min-width: 900px;
        }

        @media (max-width: 640px) {
            .orders-table-wrap {
                overflow-x: unset;
            }

            .custom-table,
            .custom-table tbody,
            .custom-table tr,
            .custom-table td {
                width: 100%;
            }

            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 12px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 12px;
                background: #fff;
            }

            .custom-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                padding: 8px 4px;
                border-bottom: 1px solid #e5e7eb;
                font-size: 13px;
                white-space: normal;
            }

            .custom-table tbody td:last-child {
                border-bottom: none;
            }

            .custom-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 12px;
                color: #6b7280;
            }

            .print-btn {
                width: 100%;
            }
        }
    </style>

  <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('printInHiddenFrame', (event) => {
            let iframe = document.getElementById('print-frame');

            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'print-frame';
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                iframe.style.visibility = 'hidden';
                document.body.appendChild(iframe);
            }

            iframe.onload = function () {
                setTimeout(() => {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500);
            };

            iframe.src = event.url + '?t=' + Date.now();
        });
    });
</script>
</div>