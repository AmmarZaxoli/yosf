
<div>
    <div class="container-fluid py-4">
        <div class="text-center my-4">
            <div class="header-container d-inline-block">
                <h3 class="header-title mb-1">إدارة السائقين</h3>
            </div>
        </div>

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <button wire:click="create" class="btn-save">
                <i class="fas fa-plus-circle me-2" style="color: white;"></i>
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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-with-btn">
                            <input type="text" class="field-input" placeholder="بحث بالاسم أو رقم الهاتف..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Drivers Table --}}
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <h5 class="section-title mb-0">قائمة السائقين</h5>
                    <small class="section-subtitle">عرض وتعديل بيانات السائقين</small>
                </div>
                <div class="ms-auto">
                    <span class="section-tag">{{ $drivers->total() }} سائق</span>
                </div>
            </div>

            <div class="section-card-body p-0">
                <div class="table-responsive">
                    <table class="orders-table">
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
                                    <td class="fw-bold">{{ $drivers->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $driver->name }}</td>
                                    <td>{{ $driver->phone ?? '—' }}</td>
                                    <td>{{ $driver->address ?? '—' }}</td>
                                    <td class="fw-bold text-success">{{ number_format($driver->delivery_price) }}</td>
                                    <td>
                                        <button wire:click="edit({{ $driver->id }})" class="btn-row-delete" title="تعديل"
                                            style="background: #e0f2fe; color: #0369a1;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">لا يوجد سائقين</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($drivers->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $drivers->links() }}
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
                                        <label class="form-label-modern">
                                            <i class="fas fa-user text-primary"></i>
                                            اسم السائق
                                            <span class="required-badge">مطلوب</span>
                                        </label>
                                        <div class="input-group-modern">
                                            <span class="input-icon">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" class="form-input-modern @error('name') is-error @enderror"
                                                wire:model="name" placeholder="أدخل اسم السائق">
                                        </div>
                                        @error('name')
                                            <span class="error-text-modern">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Phone Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-phone-alt text-success"></i>
                                            رقم الهاتف
                                        </label>
                                        <div class="input-group-modern">
                                            <span class="input-icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </span>
                                            <input type="text" class="form-input-modern @error('phone') is-error @enderror"
                                                wire:model="phone" placeholder="أدخل رقم الهاتف">
                                        </div>
                                        @error('phone')
                                            <span class="error-text-modern">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Row 2: Address and Delivery Price --}}
                                <div class="form-row-modern">
                                    {{-- Address Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-map-marker-alt text-warning"></i>
                                            العنوان
                                        </label>
                                        <div class="input-group-modern">
                                            <span class="input-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <input type="text"
                                                class="form-input-modern @error('address') is-error @enderror"
                                                wire:model="address" placeholder="أدخل العنوان">
                                        </div>
                                        @error('address')
                                            <span class="error-text-modern">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Delivery Price Field --}}
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-money-bill-wave text-info"></i>
                                            سعر التوصيل
                                            <span class="required-badge">مطلوب</span>
                                        </label>
                                        <div class="input-group-modern">
                                            <span class="input-icon">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </span>
                                            <input type="number" step="1" min="0"
                                                class="form-input-modern @error('delivery_price') is-error @enderror"
                                                wire:model="delivery_price" placeholder="0">
                                            <span class="input-suffix-modern">ر.س</span>
                                        </div>
                                        @error('delivery_price')
                                            <span class="error-text-modern">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </span>
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
                                <button type="submit" class="btn-submit-modern" wire:loading.attr="disabled">
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
                    /* backdrop-filter: blur(8px); - Removed */
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
                    margin-top: 100px;
                    background: white;
                    border-radius: 28px;
                    width: 90%;
                    max-width: 750px;
                    max-height: 90vh;
                    overflow-y: auto;
                    box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
                    animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                    position: relative;
                }

                .modal-create {
                    background: linear-gradient(145deg, #ffffff, #fafafa);
                }

                .modal-edit {
                    background: linear-gradient(145deg, #ffffff, #f8fafc);
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
                    border-bottom: 2px solid #f1f5f9;
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
                    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 12px 24px -8px rgba(59, 130, 246, 0.4);
                    transform: rotate(0deg);
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
                    color: #1e293b;
                    margin: 0 0 4px 0;
                    line-height: 1.3;
                }

                .modal-subtitle-modern {
                    font-size: 14px;
                    color: #64748b;
                    margin: 0;
                }

                .modal-close-btn {
                    width: 44px;
                    height: 44px;
                    border-radius: 14px;
                    background: #f8fafc;
                    border: 2px solid #e2e8f0;
                    color: #64748b;
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

                .form-label-modern {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 14px;
                    font-weight: 600;
                    color: #334155;
                    margin-bottom: 10px;
                }

                .form-label-modern i {
                    font-size: 16px;
                }

                .required-badge {
                    background: #fee2e2;
                    color: #ef4444;
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
                    color: #94a3b8;
                    font-size: 16px;
                    z-index: 1;
                }

                .form-input-modern {
                    width: 100%;
                    padding: 14px 45px 14px 16px;
                    border: 2px solid #e2e8f0;
                    border-radius: 16px;
                    font-size: 15px;
                    font-family: 'Tajawal', sans-serif;
                    transition: all 0.2s ease;
                    background: white;
                }

                .form-input-modern:focus {
                    outline: none;
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
                }

                .form-input-modern.is-error {
                    border-color: #ef4444;
                    background-color: #fef2f2;
                }

                .input-suffix-modern {
                    position: absolute;
                    left: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #64748b;
                    font-size: 14px;
                    font-weight: 600;
                    background: #f1f5f9;
                    padding: 4px 8px;
                    border-radius: 8px;
                }

                .error-text-modern {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    margin-top: 8px;
                    font-size: 13px;
                    color: #ef4444;
                    animation: shake 0.3s ease-in-out;
                }

                @keyframes shake {

                    0%,
                    100% {
                        transform: translateX(0);
                    }

                    25% {
                        transform: translateX(-4px);
                    }

                    75% {
                        transform: translateX(4px);
                    }
                }

                .error-text-modern i {
                    font-size: 14px;
                }

                /* Modal Footer */
                .modal-footer-modern {
                    display: flex;
                    gap: 16px;
                    justify-content: flex-end;
                    margin-top: 32px;
                    padding-top: 24px;
                    border-top: 2px dashed #e2e8f0;
                }

                .btn-cancel-modern {
                    padding: 14px 28px;
                    border: 2px solid #e2e8f0;
                    background: white;
                    color: #64748b;
                    font-weight: 600;
                    font-size: 15px;
                    border-radius: 16px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-family: 'Tajawal', sans-serif;
                }

                .btn-cancel-modern:hover {
                    background: #f8fafc;
                    border-color: #cbd5e1;
                    color: #475569;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
                }

                .btn-submit-modern {
                    padding: 14px 32px;
                    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                    border: none;
                    color: white;
                    font-weight: 600;
                    font-size: 15px;
                    border-radius: 16px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
                    font-family: 'Tajawal', sans-serif;
                }

                .btn-submit-modern:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.5);
                }

                .btn-submit-modern:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                    filter: grayscale(0.2);
                }

                .btn-submit-modern i {
                    font-size: 18px;
                }

                /* RTL Specific Adjustments */
                [dir="rtl"] .input-icon {
                    right: 16px;
                    left: auto;
                }

                [dir="rtl"] .form-input-modern {
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

                    .btn-cancel-modern,
                    .btn-submit-modern {
                        padding: 12px 20px;
                        font-size: 14px;
                    }

                    .modal-footer-modern {
                        flex-direction: column;
                    }

                    .btn-cancel-modern,
                    .btn-submit-modern {
                        width: 100%;
                        justify-content: center;
                    }
                }

                /* Custom Scrollbar */
                .modal-container::-webkit-scrollbar {
                    width: 8px;
                }

                .modal-container::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 20px;
                }

                .modal-container::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                    border-radius: 20px;
                }

                .modal-container::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #2563eb, #7c3aed);
                }
            </style>
        @endif
    </div>
</div>