<div>
    <div class="container-fluid">

        <!-- Main Content Card -->
        <div class="card shadow-sm border-0">
            <!-- Header Section inside the card -->
            <div class="card-header border-0" style="background-color: #4f46e5;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-file-invoice me-2"></i>إدارة الفواتير
                        </h4>
                        <small class="text-white opacity-75">نظام إدارة الطلبات والفواتير</small>
                    </div>
                </div>
            </div>

            <div class="card-body">

                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-lg me-3"></i>
                            <div>
                                <strong>تمت العملية بنجاح!</strong>
                                <div class="small">{{ session('success') }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                            <div>
                                <strong>خطأ!</strong>
                                <div class="small">{{ session('error') }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                            <div>
                                <strong>خطأ في البيانات!</strong>
                                <div class="small">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters Toolbar -->
                <div class="d-flex flex-wrap gap-3 justify-content-between mb-4 bg-light p-3 rounded">
                    <div class="flex-grow-1" style="max-width: 500px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0"
                                placeholder="بحث برقم الفاتورة، الهاتف، الاسم..."
                                wire:model.live="search">
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label class="text-muted small fw-bold text-nowrap">عدد الصفوف:</label>
                        <select class="form-select form-select-sm" wire:model.live="perPage" style="width: 80px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="small text-uppercase">
                            <tr>
                                <th class="py-3 ps-3 rounded-start">#</th>
                                <th class="py-3">الفاتورة</th>
                                <th class="py-3">معلومات العميل</th>
                                <th class="py-3">الشاحنة</th>
                                <th class="py-3">الطلبات</th>
                                <th class="py-3">التاريخ</th>
                                <th class="py-3 rounded-end text-end pe-3">تحكم</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($invoices as $invoice)
                                <tr wire:key="inv-{{ $invoice->id }}">
                                    <td class="ps-3 fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                            #{{ $invoice->invoice_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark mb-1">
                                                <i class="fas fa-phone-alt text-muted me-1 small"></i> {{ $invoice->phone }}
                                            </span>
                                            <small class="text-muted d-inline-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-danger me-1 small"></i>
                                                <span class="text-truncate"
                                                    style="max-width: 180px;">{{ $invoice->address }}</span>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-truck me-1"></i> {{ $invoice->truck_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $invoice->items_count ?? $invoice->items->count() }} منتجات
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $invoice->created_at->format('Y/m/d') }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-light text-primary hover-scale"
                                                wire:click="openEditModal({{ $invoice->id }})" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light text-danger hover-scale"
                                                wire:click="openDeleteModal({{ $invoice->id }})" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="mb-3 text-muted opacity-25">
                                                <i class="fas fa-box-open fa-4x"></i>
                                            </div>
                                            <h5>لا توجد بيانات</h5>
                                            <p class="text-muted small">لم يتم العثور على فواتير تطابق بحثك</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($invoices->hasPages())
                    <div class="mt-4 pt-3 border-top">
                        {{ $invoices->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- modal -->
    @if($showEditModal)
        <div class="modal-backdrop fade show" style="z-index: 1050;"></div>
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="z-index: 1055;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg" style="height: 90vh;">

                    <div class="modal-header text-white py-3" style="background-color: #4f46e5;">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded p-2 me-3">
                                <i class="fas fa-file-signature fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold">تعديل الفاتورة #{{ $editInvoiceNumber }}</h5>
                                <small class="opacity-75">تعديل بيانات العميل والمنتجات</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeEditModal"></button>
                    </div>

                    <div class="modal-body p-0 bg-light">
                        <div class="d-flex h-100">

                            <div class="sidebar-column bg-transparent border-end d-flex flex-column" style="width: 280px; min-width: 280px;">

                                <div class="p-3 bg-light border-bottom">
                                    <h6 class="text-uppercase text-muted small fw-bold mb-2">بيانات العميل</h6>
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm mb-1" placeholder="الهاتف *"
                                            wire:model="editPhone">
                                        @error('editPhone') <span class="text-danger x-small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm mb-1" placeholder="العنوان *"
                                            wire:model="editAddress">
                                        @error('editAddress') <span class="text-danger x-small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm" placeholder="الشاحنة *"
                                            wire:model="editTruckNumber">
                                        @error('editTruckNumber') <span class="text-danger x-small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <input type="date" class="form-control form-control-sm" wire:model="editTodayDate">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                                    <small class="text-muted fw-bold">المنتجات ({{ count($editOrders) }})</small>
                                </div>

                                <div class="flex-grow-1">

                                    <div class="list-group list-group-flush sidebar-column  border-end d-flex flex-column"
                                        style="width: 280px; height: 200px;">
                                        @foreach($editOrders as $index => $order)
                                            <button type="button" wire:key="nav-item-{{ $index }}"
                                                wire:click="$set('selectedProductIndex', {{ $index }})"
                                                class="list-group-item list-group-item-action border-0 rounded p-2 mb-1 d-flex align-items-center {{ $selectedProductIndex === $index ? 'active-product' : '' }}">
                                                <div class="avatar-sm  text-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="text-truncate">
                                                    <div class="fw-bold text-truncate" style="max-width: 150px;">
                                                        {{ $order['name'] ?: 'بدون اسم' }}
                                                    </div>
                                                    <small class="opacity-75">{{ $order['quantity'] }} قطعة</small>
                                                </div>
                                                @if($selectedProductIndex === $index)
                                                    <i class="fas fa-chevron-left ms-auto small"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            <div class="content-column flex-grow-1 overflow-auto p-4">

                                @if(is_numeric($selectedProductIndex) && isset($editOrders[$selectedProductIndex]))
                                    <div class="animate-fade-in">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <h5 class="fw-bold text-dark">
                                                <i class="fas fa-box-open text-primary me-2"></i> تعديل المنتج
                                                #{{ $selectedProductIndex + 1 }}
                                            </h5>
                                            <div class="d-flex gap-2"> <!-- Added wrapper with gap -->
                                                <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="showProductDeleteConfirmation({{ $selectedProductIndex }})"
                                                    title="حذف المنتج">
                                                    <i class="fas fa-trash me-1"></i> حذف المنتج
                                                </button>
                                                <button class="btn btn-outline-success" wire:click="addNewProduct">
                                                    <i class=""></i> إضافة منتج جديد
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label small text-muted">اسم المنتج <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            wire:model="editOrders.{{ $selectedProductIndex }}.name">
                                                        @error('editOrders.' . $selectedProductIndex . '.name') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small text-muted">الكمية <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control"
                                                            wire:model="editOrders.{{ $selectedProductIndex }}.quantity"
                                                            min="1">
                                                        @error('editOrders.' . $selectedProductIndex . '.quantity') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label small text-muted">رابط المنتج</label>
                                                        <input type="url" class="form-control bg-light"
                                                            wire:model="editOrders.{{ $selectedProductIndex }}.link">
                                                        @error('editOrders.' . $selectedProductIndex . '.link') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small text-muted">أيام التوصيل <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control"
                                                            wire:model="editOrders.{{ $selectedProductIndex }}.date_order"
                                                            min="1">
                                                        @error('editOrders.' . $selectedProductIndex . '.date_order') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small text-muted">تاريخ التوصيل <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" class="form-control"
                                                            wire:model="editOrders.{{ $selectedProductIndex }}.delivery_date">
                                                        @error('editOrders.' . $selectedProductIndex . '.delivery_date') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="text-muted fw-bold mb-3"><i class="far fa-images me-2"></i>صور المنتج</h6>
                                        <div class="card border-0 shadow-sm p-3">
                                            <div class="image-grid">
                                                @if(!empty($editOrders[$selectedProductIndex]['existing_images']))
                                                    @foreach($editOrders[$selectedProductIndex]['existing_images'] as $img)
                                                        <div class="image-item" wire:key="ex-img-{{ $img['id'] }}">
                                                            <img src="{{ $img['url'] }}" class="rounded">
                                                            <button type="button" class="btn-remove"
                                                                wire:click="removeExistingImage({{ $selectedProductIndex }}, {{ $img['id'] }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                @if(!empty($editOrders[$selectedProductIndex]['new_images']))
                                                    @foreach($editOrders[$selectedProductIndex]['new_images'] as $idx => $tempImg)
                                                        <div class="image-item new" wire:key="new-img-{{ $idx }}">
                                                            <img src="{{ $tempImg->temporaryUrl() }}" class="rounded">
                                                            <span class="badge-new">جديد</span>
                                                            <button type="button" class="btn-remove"
                                                                wire:click="removeNewImage({{ $selectedProductIndex }}, {{ $idx }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <!-- Upload Button -->
                                                <label class="upload-box">
                                                    <input type="file" hidden multiple accept="image/*"
                                                        wire:model="editOrders.{{ $selectedProductIndex }}.temp_images">
                                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                                    <span class="small text-muted">إضافة صور</span>
                                                </label>
                                            </div>
                                            @error('editOrders.' . $selectedProductIndex . '.new_images') <span
                                            class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- B) NEW PRODUCT FORM -->
                                @elseif($selectedProductIndex === 'new')
                                    <div class="animate-fade-in">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-bold text-success">
                                                <i class="fas fa-plus-circle me-2"></i> إضافة منتج جديد
                                            </h5>
                                        </div>

                                        <div class="card border-success border-2 shadow-sm mb-4">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label">اسم المنتج <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" wire:model="newOrder.name">
                                                        @error('newOrder.name') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">الكمية <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" wire:model="newOrder.quantity"
                                                            min="1">
                                                        @error('newOrder.quantity') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">الرابط</label>
                                                        <input type="url" class="form-control" wire:model="newOrder.link">
                                                        @error('newOrder.link') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">أيام التوصيل <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control"
                                                            wire:model="newOrder.date_order" min="1">
                                                        @error('newOrder.date_order') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">تاريخ التوصيل <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" class="form-control"
                                                            wire:model="newOrder.delivery_date">
                                                        @error('newOrder.delivery_date') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3">
                                            <h6 class="mb-3">صور المنتج الجديد</h6>
                                            <div class="image-grid">
                                                @if(!empty($newOrder['images']))
                                                    @foreach($newOrder['images'] as $idx => $img)
                                                        <div class="image-item new" wire:key="new-order-img-{{ $idx }}">
                                                            <img src="{{ $img->temporaryUrl() }}" class="rounded">
                                                            <button type="button" class="btn-remove"
                                                                wire:click="removeImageFromNewOrder({{ $idx }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <label class="upload-box">
                                                    <input type="file" hidden multiple accept="image/*"
                                                        wire:model="newOrder.temp_images">
                                                    <i class="fas fa-plus fa-2x text-success mb-2"></i>
                                                    <span class="small text-muted">اختر صور</span>
                                                </label>
                                            </div>
                                            @error('newOrder.images') <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Save New Product Button -->
                                        <div class="mt-4 text-end">
                                            <button type="button" class="btn btn-success px-4" wire:click="addOrderToEdit">
                                                <i class="fas fa-check me-2"></i> حفظ المنتج الجديد
                                            </button>
                                            <button type="button" class="btn btn-light"
                                                wire:click="$set('selectedProductIndex', 0)">
                                                إلغاء
                                            </button>
                                        </div>
                                    </div>

                                    <!-- C) EMPTY STATE -->
                                @else
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-mouse-pointer fa-3x mb-3 opacity-25"></i>
                                        <h5>اختر منتجاً من القائمة الجانبية</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white border-top py-2">

                        <button type="button" class="btn btn-primary px-4" wire:click="saveEditedInvoice"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            <span wire:loading.remove wire:target="saveEditedInvoice">
                                <i class="fas fa-save me-2"></i> حفظ الفاتورة
                            </span>
                            <span wire:loading wire:target="saveEditedInvoice">
                                <i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="modal-backdrop fade show" style="z-index: 1060;"></div>
        <div class="modal fade show d-block" tabindex="-1" style="z-index: 1065;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-body text-center p-5">
                        <div class="mb-4 text-danger opacity-75">
                            <i class="fas fa-trash-alt fa-4x"></i>
                        </div>
                        <h4 class="mb-3">تأكيد الحذف؟</h4>
                        <p class="text-muted mb-4">
                            هل أنت متأكد من حذف هذه الفاتورة؟ <br>
                            سيتم حذف جميع البيانات والمنتجات المرتبطة بها نهائياً.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary px-4"
                                wire:click="closeDeleteModal">إلغاء</button>
                            <button type="button" class="btn btn-danger px-4" wire:click="deleteInvoice">نعم، احذف</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- STYLES -->
    <style>
        .hover-scale {
            transition: transform 0.2s;
        }

        .hover-scale:hover {
            transform: scale(1.1);
        }

        /* Sidebar Styles */
        .active-product {
            background-color: #e7f1ff !important;
            color: #0d6efd !important;
            border-left: 3px solid #0d6efd !important;
        }

        .dashed-border {
            border: 2px dashed #198754;
        }

        .x-small {
            font-size: 0.75rem;
        }

        /* Image Grid System */
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
        }

        .image-item {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
            /* Square Aspect Ratio */
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .image-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-remove {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            z-index: 2;
        }

        .upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            min-height: 100px;
            transition: all 0.2s;
        }

        .upload-box:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        .badge-new {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #0d6efd;
            color: white;
            font-size: 10px;
            text-align: center;
        }

        /* Loading States */
        .opacity-50 {
            opacity: 0.5 !important;
        }

        /* Animation */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>