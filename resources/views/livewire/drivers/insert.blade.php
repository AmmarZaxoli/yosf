<div>
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
                <div class="account-pagination-wrap">
                                {{ $drivers->links('livewire::bootstrap') }}
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

                                <div class="form-row-modern">
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
        @endif
    </div>
</div>

{{-- ===== ALL STYLES ===== --}}
<style>
    /* ── TABLE TITLE HEADER (the missing piece) ── */
   
</style>
</div>