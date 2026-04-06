<div>
    <div class="container-fluid py-4">
        <div class="invoice-page-header">
            <div class="title-pill">
                <i class="fas fa-file-alt"></i>
                إدارة السائقين
            </div>
        </div>

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <button wire:click="create" class="btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i>
                إضافة سائق جديد
            </button>
        </div>

        {{-- Success Message --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="table-card mb-4">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="بحث بالاسم أو رقم الهاتف..."
                        wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        {{-- Drivers Table --}}
        <div class="table-card">
            <div class="table-title">
                <div class="title-right">
                    <i class="fas fa-truck"></i>
                    <span>قائمة السائقين</span>
                    <span class="selected-count">{{ $drivers->total() }} سائق</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم السائق</th>
                            <th>رقم الهاتف</th>
                            <th>العنوان</th>
                            <th>سعر التوصيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $index => $driver)
                            <tr>
                                <td class="table-number">{{ $drivers->firstItem() + $index }}</td>
                                <td class="t-name">{{ $driver->name }}</td>
                                <td>{{ $driver->phone ?? '—' }}</td>
                                <td>{{ $driver->address ?? '—' }}</td>
                                <td class="fw-bold text-success">{{ number_format($driver->delivery_price) }}</td>
                                <td>
                                    <div class="action-btns">
                                        <button wire:click="edit({{ $driver->id }})" class="action-btn edit" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p>لا يوجد سائقين</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($drivers->hasPages())
                <div class="table-toolbar border-top">
                    <div class="pager">
                        {{ $drivers->links() }}
                    </div>
                </div>
            @endif
        </div>

        @if($isOpen)
            <div class="modal-overlay">
                <div class="modal-container {{ $isEditing ? 'modal-edit' : 'modal-create' }}">
                    <div class="modal-header-modern">
                        <div class="modal-header-content">
                            <div class="modal-icon-wrapper">
                                <div class="modal-icon-circle">
                                    <i class="fas fa-{{ $isEditing ? 'user-edit' : 'user-plus' }}"></i>
                                </div>
                            </div>
                            <div class="modal-title-wrapper">
                                <h3 class="modal-title-modern">
                                    {{ $isEditing ? 'تعديل بيانات السائق' : 'إضافة سائق جديد' }}
                                </h3>
                                <p class="modal-subtitle-modern">
                                    {{ $isEditing ? 'قم بتعديل معلومات السائق من هنا' : 'أدخل معلومات السائق الجديد في الحقول التالية' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="modal-close-btn" wire:click="closeModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body-modern">
                        <form wire:submit.prevent="store">
                            <div class="form-grid-modern">
                                <div class="form-row-modern">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fas fa-user text-primary"></i>
                                            اسم السائق
                                            <span class="required-badge">مطلوب</span>
                                        </label>
                                        <div class="input-group-modern">

                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                wire:model="name" placeholder="أدخل اسم السائق">
                                        </div>
                                        @error('name')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Phone Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fas fa-phone-alt text-success"></i>
                                            رقم الهاتف
                                        </label>
                                        <div class="input-group-modern">
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                wire:model="phone" placeholder="أدخل رقم الهاتف">
                                        </div>
                                        @error('phone')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Row 2: Address and Delivery Price --}}
                                <div class="form-row-modern">
                                    {{-- Address Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt text-warning"></i>
                                            العنوان
                                        </label>
                                        <div class="input-group-modern">
                                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                wire:model="address" placeholder="أدخل العنوان">
                                        </div>
                                        @error('address')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Delivery Price Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fas fa-money-bill-wave text-info"></i>
                                            سعر التوصيل
                                            <span class="required-badge">مطلوب</span>
                                        </label>
                                        <div class="input-group-modern">
                                            <input type="number" step="1" min="0"
                                                class="form-control @error('delivery_price') is-invalid @enderror"
                                                wire:model="delivery_price" placeholder="0">
                                            <span class="input-suffix-modern">ر.س</span>
                                        </div>
                                        @error('delivery_price')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="modal-footer-modern">
                                <button type="button" class="btn-cancel-modern" wire:click="closeModal">
                                    <i class="fas fa-times"></i>
                                    <span>إلغاء</span>
                                </button>
                                <button type="submit" class="btn-primary-custom" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="store">
                                        <i class="fas fa-{{ $isEditing ? 'save' : 'check-circle' }}"></i>
                                        <span>{{ $isEditing ? 'تحديث البيانات' : 'إضافة السائق' }}</span>
                                    </span>
                                    <span wire:loading wire:target="store">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <span>{{ $isEditing ? 'جاري التحديث...' : 'جاري الإضافة...' }}</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <style>
                .modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.6);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                    animation: overlayFade 0.3s ease-out;
                }

                @keyframes overlayFade {
                    from {
                        opacity: 0;
                    }

                    to {
                        opacity: 1;
                    }
                }

                /* Modal Container */
                .modal-container {

                    background: var(--bg-card);
                    border-radius: 28px;
                    width: 90%;
                    max-width: 750px;
                    max-height: 90vh;
                    overflow-y: auto;
                    box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
                    animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                    position: relative;
                    border: 1px solid var(--border);
                }

                .modal-create {
                    background: var(--bg-card);
                }

                .modal-edit {
                    background: var(--bg-card);
                }

                @keyframes modalSlideUp {
                    from {
                        opacity: 0;
                        transform: translateY(40px) scale(0.95);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                /* Modal Header */
                .modal-header-modern {
                    padding: 28px 32px;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    position: relative;
                }

                .modal-header-content {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }

                .modal-icon-wrapper {
                    position: relative;
                }

                .modal-icon-circle {
                    width: 64px;
                    height: 64px;
                    background: linear-gradient(135deg, var(--primary), var(--primary-light));
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 12px 24px -8px rgba(26, 107, 90, 0.4);
                    transition: transform 0.3s ease;
                }

                .modal-container:hover .modal-icon-circle {
                    transform: rotate(5deg);
                }

                .modal-icon-circle i {
                    font-size: 32px;
                    color: white;
                }

                .modal-title-wrapper {
                    flex: 1;
                }

                .modal-title-modern {
                    font-size: 24px;
                    font-weight: 700;
                    color: var(--text);
                    margin: 0 0 4px 0;
                    line-height: 1.3;
                }

                .modal-subtitle-modern {
                    font-size: 14px;
                    color: var(--text-secondary);
                    margin: 0;
                }

                .modal-close-btn {
                    width: 44px;
                    height: 44px;
                    border-radius: 14px;
                    background: var(--bg);
                    border: 1px solid var(--border);
                    color: var(--text-secondary);
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.2s ease;
                }

                .modal-close-btn:hover {
                    background: #fee2e2;
                    color: #ef4444;
                    border-color: #fecaca;
                    transform: rotate(90deg);
                }

                .modal-close-btn i {
                    font-size: 20px;
                }

                .btn-cancel-modern {
                    padding: 12px 24px;
                    border: 2px solid var(--border);
                    background: var(--bg-card);
                    color: var(--text-secondary);
                    font-weight: 600;
                    font-size: 15px;
                    border-radius: 14px;
                    cursor: pointer;
                    transition: var(--transition);
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    font-family: inherit;
                }

                .btn-cancel-modern:hover {
                    background: var(--bg);
                    color: var(--text);
                    transform: translateY(-2px);
                }

                /* Modal Body */
                .modal-body-modern {
                    padding: 32px;
                }

                .form-grid-modern {
                    display: flex;
                    flex-direction: column;
                    gap: 24px;
                }

                .form-row-modern {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                }

                .form-group-modern {
                    position: relative;
                }

                .form-label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 14px;
                    font-weight: 600;
                    color: var(--text);
                    margin-bottom: 10px;
                }

                .form-label i {
                    font-size: 16px;
                }

                .required-badge {
                    background: rgba(239, 68, 68, 0.12);
                    color: #dc2626;
                    font-size: 11px;
                    font-weight: 600;
                    padding: 3px 8px;
                    border-radius: 20px;
                    margin-right: 8px;
                }

                .input-group-modern {
                    position: relative;
                    display: flex;
                    align-items: center;
                }

                .input-icon {
                    position: absolute;
                    right: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: var(--text-secondary);
                    font-size: 16px;
                    z-index: 1;
                }

                .form-control {
                    width: 100%;
                    padding: 14px 45px 14px 16px;
                }

                .form-control.is-invalid {
                    border-color: #ef4444;
                    background-color: rgba(239, 68, 68, 0.05);
                }

                .input-suffix-modern {
                    position: absolute;
                    left: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: var(--text-secondary);
                    font-size: 14px;
                    font-weight: 600;
                    background: var(--bg);
                    padding: 4px 8px;
                    border-radius: 8px;
                }

                .error-message {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    margin-top: 8px;
                    font-size: 13px;
                    color: #ef4444;
                }

                .error-message i {
                    font-size: 14px;
                }

                /* Modal Footer */
                .modal-footer-modern {
                    display: flex;
                    gap: 16px;
                    justify-content: flex-end;
                    margin-top: 32px;
                    padding-top: 24px;
                    border-top: 1px dashed var(--border);
                }

                /* RTL Specific Adjustments */
                [dir="rtl"] .input-icon {
                    right: 16px;
                    left: auto;
                }

                [dir="rtl"] .form-control {
                    padding: 14px 45px 14px 16px;
                }

                [dir="rtl"] .input-suffix-modern {
                    left: auto;
                    right: 16px;
                }

                [dir="rtl"] .modal-footer-modern {
                    justify-content: flex-start;
                }

                /* Loading Spinner */
                .fa-spinner {
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    from {
                        transform: rotate(0deg);
                    }

                    to {
                        transform: rotate(360deg);
                    }
                }

                /* Responsive Design */
                @media (max-width: 640px) {
                    .modal-container {
                        width: 95%;
                        max-height: 95vh;
                    }

                    .modal-header-modern {
                        padding: 20px;
                    }

                    .modal-icon-circle {
                        width: 48px;
                        height: 48px;
                    }

                    .modal-icon-circle i {
                        font-size: 24px;
                    }

                    .modal-title-modern {
                        font-size: 20px;
                    }

                    .modal-subtitle-modern {
                        font-size: 13px;
                    }

                    .modal-body-modern {
                        padding: 20px;
                    }

                    .form-row-modern {
                        grid-template-columns: 1fr;
                        gap: 16px;
                    }

                    .modal-footer-modern {
                        flex-direction: column;
                    }

                    .btn-outline-custom,
                    .btn-primary-custom {
                        width: 100%;
                        justify-content: center;
                    }
                }

                /* Custom Scrollbar */
                .modal-container::-webkit-scrollbar {
                    width: 8px;
                }

                .modal-container::-webkit-scrollbar-track {
                    background: var(--bg);
                    border-radius: 20px;
                }

                .modal-container::-webkit-scrollbar-thumb {
                    background: var(--primary);
                    border-radius: 20px;
                }

                .modal-container::-webkit-scrollbar-thumb:hover {
                    background: var(--primary-dark);
                }
            </style>
        @endif
    </div>
</div>