<div>
    <div class="container-fluid py-4">
        <div class="invoice-page-header">
            <div class="title-pill">
                <i class="bi bi-bar-chart-steps"></i>
                 لوحة المحاسبة
            </div>
        </div>

        <div class="filters-card">
            <div class="row g-3">
                <!-- Date Range -->
                <div class="col-md-5">
                    <input type="date" class="form-input-modern" wire:model="dateFrom" placeholder="من تاريخ">
                </div>
                <div class="col-md-5">
                    <input type="date" class="form-input-modern" wire:model="dateTo" placeholder="إلى تاريخ">
                </div>
                <!-- Search Button -->
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" wire:click="loadData"
                        style="border-radius:16px; height:48px;">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Total Invoices -->
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-icon bg-primary">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">إجمالي جميع المبيعات</span>
                        <span class="summary-value">{{ number_format($totalInvoices ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-icon bg-danger">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">إجمالي المصاريف</span>
                        <span class="summary-value">{{ number_format($totalExpenses ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="col-md-4">
                <div class="summary-card {{ $this->netProfit >= 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                    <div class="summary-icon {{ $this->netProfit >= 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="summary-details">
                        <span class="summary-label">صافي الربح</span>
                        <span class="summary-value">{{ number_format($this->netProfit) }}</span>
                    </div>
                </div>
            </div>
        </div>


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