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
                    {{ $accounts->links() }}
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
        .page-title-custom {
            color: var(--text);
            font-weight: 800;
        }

        .account-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            background: var(--bg-card);
        }

        .account-card-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .account-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        .account-card-title {
            color: var(--text);
            font-weight: 700;
        }

        .account-card-subtitle {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .account-card-count {
            background: rgba(26, 107, 90, 0.10);
            color: var(--primary);
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .account-user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .account-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            flex-shrink: 0;
        }

        .account-avatar.admin {
            background: #8b5cf6;
        }

        .account-avatar.user {
            background: #10b981;
        }

        .account-role-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .account-role-badge.admin {
            background: rgba(139, 92, 246, 0.12);
            color: #8b5cf6;
        }

        .account-role-badge.user {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .account-password-chip {
            font-family: monospace;
            background: var(--bg);
            color: var(--text);
            padding: 4px 8px;
            border-radius: 8px;
            border: 1px solid var(--border);
            display: inline-block;
        }

        .account-pagination-wrap {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            background: var(--bg-card);
        }

        .account-pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 5px;
        }

        .account-pagination-wrap .page-link {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            border-radius: 10px;
            box-shadow: none;
        }

        .account-pagination-wrap .page-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .account-pagination-wrap .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .account-pagination-wrap .page-item.disabled .page-link {
            background: var(--bg-card);
            color: var(--text-secondary);
            border-color: var(--border);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .modal-container {
            background: var(--bg-card);
            color: var(--text);
            width: 100%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 28px;
            border: 1px solid var(--border);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
            margin-top: 60px;
        }

        .modal-create {
            border-top: 4px solid #10b981;
        }

        .modal-edit {
            border-top: 4px solid #3b82f6;
        }

        .modal-header-modern {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .modal-header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .modal-icon-circle {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            flex-shrink: 0;
            box-shadow: 0 12px 24px -8px rgba(59, 130, 246, 0.4);
        }

        .modal-title-modern {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .modal-subtitle-modern {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        .modal-body-modern {
            padding: 28px;
        }

        .account-form-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .account-input-wrap {
            position: relative;
        }

        .account-input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            z-index: 1;
        }

        .account-modal-input {
            padding-right: 44px !important;
        }

        .account-modal-select {
            padding-left: 40px !important;
        }

        .account-required-badge,
        .account-optional-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            margin-right: 8px;
        }

        .account-required-badge {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        .account-optional-badge {
            background: var(--bg);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .account-help-text {
            color: var(--text-secondary);
            margin-top: 5px;
            display: block;
        }

        .modal-footer-modern {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px dashed var(--border);
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

        .btn-submit-modern {
            padding: 12px 28px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            border-radius: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
            font-family: inherit;
        }

        .btn-submit-modern:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.5);
        }

        .btn-submit-modern:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        @media (max-width: 640px) {
            .modal-container {
                width: 95%;
                max-height: 95vh;
            }

            .modal-header-modern,
            .modal-body-modern {
                padding: 20px;
            }

            .modal-icon-circle {
                width: 48px;
                height: 48px;
                font-size: 22px;
            }

            .modal-title-modern {
                font-size: 20px;
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
    </style>
</div>