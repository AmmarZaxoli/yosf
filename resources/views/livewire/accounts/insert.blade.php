<div>
    <div class="container-fluid py-4">
        <div class="invoice-page-header">
            <div class="title-pill">
                <i class="fas fa-file-alt"></i>
                إدارة المستخدمين
            </div>
        </div>

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button wire:click="create" class="btn-save-invoice">
                <i class="fas fa-plus-circle"></i>
                إضافة مستخدم جديد
            </button>
        </div>

        {{-- Success Message --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Search --}}
        <div class="table-card mb-4">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="بحث بالاسم..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        {{-- Accounts Table --}}
        <div class="table-card">
            <div class="account-card-header">
                <div class="account-card-header-left">
                    <div class="account-card-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h5 class="account-card-title mb-0">قائمة الحسابات</h5>
                        <small class="account-card-subtitle">عرض وتعديل بيانات الحسابات</small>
                    </div>
                </div>

                <span class="account-card-count">{{ $accounts->total() }} حساب</span>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الدور</th>
                            <th>كلمة المرور</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($accounts as $index => $account)
                            <tr>
                                <td class="fw-bold">
                                    {{ $accounts->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="account-user-cell">
                                        <div class="account-avatar {{ $account->role == 'admin' ? 'admin' : 'user' }}">
                                            {{ mb_substr($account->name, 0, 1) }}
                                        </div>
                                        <span class="fw-semibold">{{ $account->name }}</span>
                                    </div>
                                </td>

                                <td>
                                    @if($account->role == 'admin')
                                        <span class="account-role-badge admin">
                                            <i class="fas fa-crown me-1"></i>
                                            مسؤل
                                        </span>
                                    @else
                                        <span class="account-role-badge user">
                                            <i class="fas fa-user me-1"></i>
                                            مستخدم
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="account-password-chip">
                                        {{ $account->password }}
                                    </span>
                                </td>

                                <td>
                                    {{ $account->created_at ? $account->created_at->format('Y-m-d') : '—' }}
                                </td>

                                <td>
                                    <div class="action-btns">
                                        <button wire:click="edit({{ $account->id }})" class="action-btn edit" title="تعديل"
                                            type="button">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if(auth()->id() != $account->id)
                                            <button wire:click="delete({{ $account->id }})" class="action-btn delete"
                                                title="حذف" type="button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-empty">
                                    <i class="fas fa-users"></i>
                                    <p>لا يوجد حسابات</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($accounts->hasPages())
                <div class="account-pagination-wrap">
                                {{ $accounts->links('livewire::bootstrap') }}
                </div>
            @endif
        </div>

        {{-- Create / Edit Modal --}}
        @if($isOpen)
            <div class="modal-overlay" wire:click.self="closeModal">
                <div class="modal-container {{ $isEditing ? 'modal-edit' : 'modal-create' }}">
                    <div class="modal-header-modern">
                        <div class="modal-header-content">
                            <div class="modal-icon-circle">
                                <i class="fas fa-{{ $isEditing ? 'user-edit' : 'user-plus' }}"></i>
                            </div>

                            <div class="modal-title-wrapper">
                                <h3 class="modal-title-modern">
                                    {{ $isEditing ? 'تعديل بيانات الحساب' : 'إضافة حساب جديد' }}
                                </h3>
                                <p class="modal-subtitle-modern">
                                    {{ $isEditing ? 'قم بتعديل معلومات الحساب' : 'أدخل معلومات الحساب الجديد' }}
                                </p>
                            </div>
                        </div>

                        <button type="button" class="modal-close-btn" wire:click="closeModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body-modern">
                        <form wire:submit.prevent="store">
                            <div class="account-form-grid">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        الاسم
                                    </label>
                                    <div class="account-input-wrap">
                                        <span class="account-input-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control account-modal-input @error('name') is-invalid @enderror"
                                            wire:model="name" placeholder="أدخل الاسم">
                                    </div>
                                    @error('name')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-user-tag text-warning me-1"></i>
                                        الدور
                                    </label>
                                    <div class="account-input-wrap">
                                        <span class="account-input-icon">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                        <select
                                            class="form-select account-modal-input account-modal-select @error('role') is-invalid @enderror"
                                            wire:model="role">
                                            <option value="">اختر الدور</option>
                                            <option value="admin">مسؤل</option>
                                            <option value="user">مستخدم</option>
                                        </select>
                                    </div>
                                    @error('role')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-lock text-secondary me-1"></i>
                                        كلمة المرور
                                        @if(!$account_id)
                                            <span class="account-required-badge">مطلوب</span>
                                        @else
                                            <span class="account-optional-badge">اختياري</span>
                                        @endif
                                    </label>
                                    <div class="account-input-wrap">
                                        <span class="account-input-icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control account-modal-input @error('password') is-invalid @enderror"
                                            wire:model="password"
                                            placeholder="{{ $isEditing ? 'كلمة المرور الحالية' : 'أدخل كلمة المرور' }}">
                                    </div>
                                    @error('password')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @if($isEditing)
                                        <small class="account-help-text">
                                            <i class="fas fa-info-circle"></i>
                                            يمكنك تعديل كلمة المرور أو تركها كما هي
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer-modern">
                                <button type="button" class="btn-cancel-modern" wire:click="closeModal">
                                    <i class="fas fa-times"></i>
                                    <span>إلغاء</span>
                                </button>

                                <button type="submit" class="btn-submit-modern" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="store">
                                        <i class="fas fa-{{ $isEditing ? 'save' : 'check-circle' }}"></i>
                                        <span>{{ $isEditing ? 'تحديث البيانات' : 'إضافة الحساب' }}</span>
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

    <style>
       
    </style>
</div>