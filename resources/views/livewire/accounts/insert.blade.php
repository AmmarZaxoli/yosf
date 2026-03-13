<div>
    <div class="container-fluid py-4">
        <div class="text-center my-4">
            <div class="header-container d-inline-block">
                <h3 class="header-title mb-1">إدارة الحسابات</h3>
            </div>
        </div>

        {{-- Page Header --}}
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <button wire:click="create" class="btn-save">
                <i class="fas fa-plus-circle me-2" style="color: white;"></i>
                إضافة حساب جديد
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

        {{-- Error Message --}}
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-with-btn">
                            <input type="text" class="field-input" placeholder="بحث بالاسم..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accounts Table --}}
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h5 class="section-title mb-0">قائمة الحسابات</h5>
                    <small class="section-subtitle">عرض وتعديل بيانات الحسابات</small>
                </div>
                <div class="ms-auto">
                    <span class="section-tag">{{ $accounts->total() }} حساب</span>
                </div>
            </div>

            <div class="section-card-body p-0">
                <div class="table-responsive">
                    <table class="orders-table">
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
                                    <td class="fw-bold">{{ $accounts->firstItem() + $index }}</td>
                                    <td class="fw-semibold">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle"
                                                style="background: {{ $account->role == 'admin' ? '#8b5cf6' : '#10b981' }}; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                {{ substr($account->name, 0, 1) }}
                                            </div>
                                            {{ $account->name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($account->role == 'admin')
                                            <span class="badge-role"
                                                style="background: #8b5cf620; color: #8b5cf6; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-crown me-1"></i> مسؤل
                                            </span>
                                        @else
                                            <span class="badge-role"
                                                style="background: #10b98120; color: #10b981; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-user me-1"></i> مستخدم
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            style="font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 8px;">
                                            {{ $account->password }}
                                        </span>
                                    </td>
                                    <td>{{ $account->created_at ? $account->created_at->format('Y-m-d') : '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button wire:click="edit({{ $account->id }})" class="btn-row-delete"
                                                title="تعديل"
                                                style="background: #e0f2fe; color: #0369a1; border: none; width: 35px; height: 35px; border-radius: 10px;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(auth()->id() != $account->id)
                                                <button wire:click="delete({{ $account->id }})" class="btn-row-delete"
                                                    title="حذف"
                                                    style="background: #fee2e2; color: #dc2626; border: none; width: 35px; height: 35px; border-radius: 10px;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">لا يوجد حسابات</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($accounts->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $accounts->links() }}
                </div>
            @endif
        </div>

        {{-- Create/Edit Modal --}}
        @if($isOpen)
            <div class="modal-overlay" wire:click.self="closeModal">
                <div class="modal-container {{ $isEditing ? 'modal-edit' : 'modal-create' }}" style="max-width: 550px;">
                    {{-- Modal Header --}}
                    <div class="modal-header-modern">
                        <div class="modal-header-content">
                            <div class="modal-icon-wrapper">
                                <div class="modal-icon-circle">
                                    <i class="fas fa-{{ $isEditing ? 'user-edit' : 'user-plus' }}"></i>
                                </div>
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

                    {{-- Modal Body --}}
                    <div class="modal-body-modern">
                        <form wire:submit.prevent="store">
                            <div class="form-grid-modern">
                                {{-- Name Field --}}
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-user text-primary"></i>
                                        الاسم
                                        <span class="required-badge">مطلوب</span>
                                    </label>
                                    <div class="input-group-modern">
                                        <span class="input-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-input-modern @error('name') is-error @enderror"
                                            wire:model="name" placeholder="أدخل الاسم">
                                    </div>
                                    @error('name')
                                        <span class="error-text-modern">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Role Field --}}
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-user-tag text-warning"></i>
                                        الدور
                                        <span class="required-badge">مطلوب</span>
                                    </label>
                                    <div class="input-group-modern">
                                        <span class="input-icon">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                        <select class="form-input-modern @error('role') is-error @enderror"
                                            wire:model="role">
                                            <option value="">اختر الدور</option>
                                            <option value="admin">مسؤل</option>
                                            <option value="user">مستخدم</option>
                                        </select>
                                    </div>
                                    @error('role')
                                        <span class="error-text-modern">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                {{-- Password Field (No Confirmation) --}}
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-lock text-secondary"></i>
                                        كلمة المرور
                                        @if(!$account_id)
                                            <span class="required-badge">مطلوب</span>
                                        @else
                                            <span class="optional-badge">اختياري</span>
                                        @endif
                                    </label>
                                    <div class="input-group-modern">
                                        <span class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="text" class="form-input-modern @error('password') is-error @enderror"
                                            wire:model="password"
                                            placeholder="{{ $isEditing ? 'كلمة المرور الحالية' : 'أدخل كلمة المرور' }}">
                                    </div>
                                    @error('password')
                                        <span class="error-text-modern">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    @if($isEditing)
                                        <small style="color: #64748b; margin-top: 5px; display: block;">
                                            <i class="fas fa-info-circle"></i>
                                            يمكنك تعديل كلمة المرور أو تركها كما هي
                                        </small>
                                    @endif
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
        /* Modal Overlay */
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
            background: white;
            margin-top: 100px;
            border-radius: 28px;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .modal-create {
            border-top: 4px solid #10b981;
        }

        .modal-edit {
            border-top: 4px solid #3b82f6;
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
            padding: 24px 28px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .modal-header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .modal-icon-wrapper {
            position: relative;
        }

        .modal-icon-circle {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 24px -8px rgba(59, 130, 246, 0.4);
            transition: transform 0.3s ease;
        }

        .modal-container:hover .modal-icon-circle {
            transform: rotate(5deg);
        }

        .modal-icon-circle i {
            font-size: 28px;
            color: white;
        }

        .modal-title-wrapper {
            flex: 1;
        }

        .modal-title-modern {
            font-size: 22px;
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
            width: 40px;
            height: 40px;
            border-radius: 12px;
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
            font-size: 18px;
        }

        /* Modal Body */
        .modal-body-modern {
            padding: 28px;
        }

        .form-grid-modern {
            display: flex;
            flex-direction: column;
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
            margin-bottom: 8px;
            flex-wrap: wrap;
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

        .optional-badge {
            background: #f1f5f9;
            color: #64748b;
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
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            z-index: 1;
        }

        .form-input-modern {
            width: 100%;
            padding: 12px 45px 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
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

        select.form-input-modern {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 14px center;
            padding: 12px 45px 12px 40px;
        }

        .error-text-modern {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
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
            gap: 12px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 2px dashed #e2e8f0;
        }

        .btn-cancel-modern {
            padding: 12px 24px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #64748b;
            font-weight: 600;
            font-size: 15px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
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
            padding: 12px 28px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 15px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
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
        }

        .btn-submit-modern i {
            font-size: 16px;
        }

        /* RTL Specific */
        [dir="rtl"] .input-icon {
            right: 14px;
            left: auto;
        }

        [dir="rtl"] .form-input-modern {
            padding: 12px 45px 12px 16px;
        }

        [dir="rtl"] select.form-input-modern {
            background-position: left 14px center;
            padding: 12px 45px 12px 40px;
        }

        [dir="rtl"] .modal-footer-modern {
            justify-content: flex-start;
        }

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

            .modal-body-modern {
                padding: 20px;
            }

            .btn-cancel-modern,
            .btn-submit-modern {
                padding: 10px 20px;
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
    </style>
</div>