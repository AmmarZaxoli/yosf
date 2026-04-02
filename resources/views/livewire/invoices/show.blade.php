<div>
    <div class="container-fluid py-4">
        <div class="text-center my-4">
            <div class="header-container d-inline-block">
                <h3 class="header-title mb-1">إدارة الفواتير</h3>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="filters-card">
            <div class="filters-grid">
                <div class="search-box-modern">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-input-modern"
                        placeholder="بحث برقم الفاتورة، اسم العميل، أو الهاتف..." wire:model.live="search">
                </div>

                <div class="d-flex gap-2 align-items-center">
                    <div class="date-box-modern flex-grow-1">
                        <i class="fas fa-calendar date-icon"></i>
                        <input type="date" class="form-input-modern" wire:model.live="dateFilter">

                    </div>

                    @if (count($selectedInvoices) > 0)
                        <button class="btn btn-success px-4 py-2" style="white-space: nowrap; border-radius: 16px;"
                            wire:click="openDriverModal">
                            <i class="fas fa-truck me-2"></i>
                            تعيين للسائق ({{ count($selectedInvoices) }})
                        </button>
                    @else
                        <button class="btn btn-secondary px-4 py-2"
                            style="white-space: nowrap; border-radius: 16px; opacity: 0.6; cursor: not-allowed;"
                            disabled>
                            <i class="fas fa-truck me-2"></i>
                            تعيين للسائق
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Invoices Table --}}
        <div class="table-card">
            <div class="table-card-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div>
                        <h5 class="header-title">قائمة الفواتير</h5>
                        <p class="header-subtitle">عرض وإدارة جميع الفواتير</p>
                    </div>
                </div>
                <span class="badge-count-modern">{{ $invoices->total() }} فاتورة</span>
            </div>

            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th width="3%">
                                <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                            </th>
                            <th width="5%">#</th>
                            <th width="8%">رقم الفاتورة</th>
                            <th width="10%">العميل</th>
                            <th width="10%">رقم الهاتف</th>
                            <th width="15%">العنوان</th>
                            <th width="8%">التراك</th>
                            <th width="8%">التاريخ</th>
                            <th width="8%">المنتجات</th>
                            <th width="8%">الكمية</th>
                            <th width="8%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $index => $invoice)
                            <tr wire:key="invoice-{{ $invoice->id }}">
                                <td class="text-center">
                                    <input type="checkbox" wire:model.live="selectedInvoices"
                                        value="{{ $invoice->id }}" class="form-check-input">
                                </td>
                                <td class="text-center">
                                    <span
                                        class="row-number">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="invoice-badge">#{{ $invoice->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="cell-with-icon">
                                        <i class="fas fa-user-circle" style="color: #3b82f6;"></i>
                                        {{ $invoice->customer->name }}
                                    </div>
                                </td>
                                <td class="text-center" dir="ltr">
                                    <span class="phone-number">{{ $invoice->customer->phone }}</span>
                                </td>
                                <td>
                                    <div class="address-cell" title="{{ $invoice->address }}">
                                        <i class="fas fa-map-marker-alt" style="color: #ef4444;"></i>
                                        {{ Str::limit($invoice->customer->address, 25) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="truck-badge">{{ $invoice->id_truck ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="date-badge">{{ $invoice->customer->delivery_date ? $invoice->customer->delivery_date->format('Y-m-d') : '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge info">{{ $invoice->items_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge success">{{ $invoice->total_quantity }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action edit" wire:click="openEditModal({{ $invoice->id }})"
                                            title="تعديل الفاتورة">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="empty-state">
                                    <div class="empty-state-content">
                                        <i class="fas fa-file-invoice fa-4x"></i>
                                        <h5>لا توجد فواتير</h5>
                                        <p>قم بإنشاء فاتورة جديدة للبدء</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($invoices->hasPages())
                <div class="pagination-links">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

        {{-- Edit Modal --}}
        @if ($showEditModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg" style="height: 85vh;">
                        <div class="modal-header text-white py-3"
                            style="background: linear-gradient(135deg, #4f46e5, #06b6d4);height: 45px;!important;">
                            <div class="d-flex align-items-center" style="height: 1px;">
                                <div class="bg-white bg-opacity-25 rounded p-2 me-3">
                                    <i class="fas fa-file-signature fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="modal-title fw-bold">تعديل الفاتورة #{{ $editInvoiceNumber }}</h6>
                                </div>
                            </div>
                            <button type="button" class="modal-close-btn" wire:click="closeEditModal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body p-0 bg-light" style="height: calc(85vh - 130px);">
                            <div class="d-flex h-100">
                                <!-- Sidebar Column -->
                                <div class="sidebar-column bg-white border-end d-flex flex-column"
                                    style="width: 280px;">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-2">بيانات العميل</h6>
                                        <div class="mb-2">
                                            <input type="text" class="form-control form-control-sm mb-1"
                                                placeholder="الهاتف *" wire:model="editPhone">
                                            @error('editPhone')
                                                <span class="text-danger x-small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control form-control-sm mb-1"
                                                placeholder="العنوان *" wire:model="editAddress">
                                            @error('editAddress')
                                                <span class="text-danger x-small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <input type="text" class="form-control form-control-sm"
                                                placeholder="الشاحنة *" wire:model="editTruckNumber">
                                            @error('editTruckNumber')
                                                <span class="text-danger x-small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mt-2">
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model="editTodayDate">
                                        </div>
                                    </div>

                                    <div class="p-3 bg-white border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted fw-bold">المنتجات
                                                ({{ count($editOrders) }})</small>
                                            <button class="btn btn-sm btn-success" wire:click="addNewProduct">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 overflow-auto bg-white">
                                        <div class="list-group list-group-flush" style="width: 200px;">
                                            @foreach ($editOrders as $index => $order)
                                                <button type="button" wire:key="nav-item-{{ $index }}"
                                                    wire:click="$set('selectedProductIndex', {{ $index }})"
                                                    class="list-group-item list-group-item-action border-0 rounded-0 p-3 d-flex align-items-center {{ $selectedProductIndex === $index ? 'active-product' : '' }}">
                                                    <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div class="text-truncate flex-grow-1">
                                                        <div class="fw-bold text-truncate small">
                                                            {{ $order['name'] ?: 'بدون اسم' }}
                                                        </div>
                                                        <small class="opacity-75">{{ $order['quantity'] }}
                                                            قطعة</small>
                                                    </div>
                                                    @if ($selectedProductIndex === $index)
                                                        <i class="fas fa-chevron-left ms-auto small"></i>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Column -->
                                <div class="content-column flex-grow-1 overflow-auto p-4"
                                    style="background-color: #f8fafc;">
                                    @if ($selectedProductIndex === 'new')
                                        {{-- New Product Form --}}
                                        <div class="animate-fade-in">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="fw-bold text-success">
                                                    <i class="fas fa-plus-circle me-2"></i> إضافة منتج جديد
                                                </h6>
                                            </div>

                                            {{-- New Product Card with White Background --}}
                                            <div
                                                class="card border-success border-2 shadow-sm mb-4 product-card-white">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="form-label small text-muted">اسم المنتج <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                wire:model="newOrder.name">
                                                            @error('newOrder.name')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted">الكمية <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                wire:model="newOrder.quantity" min="1">
                                                            @error('newOrder.quantity')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label small text-muted">الرابط</label>
                                                            <input type="url" class="form-control"
                                                                wire:model="newOrder.link">
                                                            @error('newOrder.link')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small text-muted">أيام التوصيل
                                                                <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                wire:model="newOrder.date_order" min="1">
                                                            @error('newOrder.date_order')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small text-muted">تاريخ
                                                                التوصيل</label>
                                                            <input type="date" class="form-control"
                                                                wire:model="newOrder.delivery_date">
                                                            @error('newOrder.delivery_date')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- New Product Images Card with White Background --}}
                                            <div class="card shadow-sm border-0 product-card-white">
                                                <div class="card-body">
                                                    <h6 class="mb-3 small text-muted fw-bold">صور المنتج الجديد</h6>
                                                    <div class="image-grid">
                                                        @if (!empty($newOrder['images']))
                                                            @foreach ($newOrder['images'] as $idx => $img)
                                                                <div class="image-item new position-relative"
                                                                    wire:key="new-order-img-{{ $idx }}">
                                                                    <img src="{{ $img->temporaryUrl() }}"
                                                                        class="rounded"
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                    <button type="button"
                                                                        class="btn-remove position-absolute top-0 end-0 bg-danger text-white border-0 rounded-circle"
                                                                        style="width: 20px; height: 20px; font-size: 12px;"
                                                                        wire:click="removeImageFromNewOrder({{ $idx }})">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <label
                                                            class="upload-box d-flex flex-column align-items-center justify-content-center border rounded p-2"
                                                            style="width: 80px; height: 80px; cursor: pointer;">
                                                            <input type="file" hidden multiple accept="image/*"
                                                                wire:model="newOrder.temp_images">
                                                            <i class="fas fa-plus text-success mb-1"></i>
                                                            <span class="small text-muted"
                                                                style="font-size: 10px;">اختر</span>
                                                        </label>
                                                    </div>
                                                    @error('newOrder.temp_images')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mt-4 text-end">
                                                <button style="background-color:#cbd5e1" type="button"
                                                    class="btn btn-light" wire:click="cancelNewProduct">
                                                    إلغاء
                                                </button>
                                                <button type="button" class="btn btn-success px-4"
                                                    wire:click="addOrderToEdit">
                                                    <i class="fas fa-check me-2"></i> حفظ المنتج الجديد
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($selectedProductIndex !== null && isset($editOrders[$selectedProductIndex]))
                                        {{-- Edit Existing Product --}}
                                        <div class="animate-fade-in" wire:key="product-{{ $selectedProductIndex }}">
                                            <div class="d-flex justify-content-between align-items-start mb-4">
                                                <h5 class="fw-bold text-dark">
                                                    <i class="fas fa-box-open text-primary me-2"></i> تعديل المنتج
                                                    #{{ $selectedProductIndex + 1 }}
                                                </h5>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="showProductDeleteConfirmation({{ $selectedProductIndex }})"
                                                    title="حذف المنتج">
                                                    <i class="fas fa-trash me-1"></i> حذف
                                                </button>
                                            </div>

                                            {{-- Product Card with White Background --}}
                                            <div class="card border-0 shadow-sm mb-4 product-card-white">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="form-label small text-muted">اسم المنتج <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.name"
                                                                wire:key="name-{{ $selectedProductIndex }}-{{ time() }}">
                                                            @error('editOrders.' . $selectedProductIndex . '.name')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted">الكمية <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.quantity"
                                                                min="1"
                                                                wire:key="qty-{{ $selectedProductIndex }}-{{ time() }}">
                                                            @error('editOrders.' . $selectedProductIndex . '.quantity')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label small text-muted">رابط
                                                                المنتج</label>
                                                            <input type="url" class="form-control"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.link"
                                                                wire:key="link-{{ $selectedProductIndex }}-{{ time() }}">
                                                            @error('editOrders.' . $selectedProductIndex . '.link')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small text-muted">أيام التوصيل
                                                                <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.date_order"
                                                                min="1"
                                                                wire:key="date-{{ $selectedProductIndex }}-{{ time() }}">
                                                            @error('editOrders.' . $selectedProductIndex .
                                                                '.date_order')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small text-muted">تاريخ
                                                                التوصيل</label>
                                                            <input type="date" class="form-control"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.delivery_date"
                                                                wire:key="delivery-{{ $selectedProductIndex }}-{{ time() }}">
                                                            @error('editOrders.' . $selectedProductIndex .
                                                                '.delivery_date')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Images Card with White Background --}}
                                            <div class="card border-0 shadow-sm product-card-white">
                                                <div class="card-body">
                                                    <h6 class="text-muted fw-bold mb-3"><i
                                                            class="far fa-images me-2"></i>صور
                                                        المنتج</h6>
                                                    <div class="image-grid">
                                                        @if (!empty($editOrders[$selectedProductIndex]['existing_images']))
                                                            @foreach ($editOrders[$selectedProductIndex]['existing_images'] as $img)
                                                                <div class="image-item position-relative"
                                                                    wire:key="ex-img-{{ $img['id'] }}-{{ $selectedProductIndex }}">
                                                                    <img src="{{ $img['url'] }}" class="rounded"
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                    <button type="button"
                                                                        class="btn-remove position-absolute top-0 end-0 bg-danger text-white border-0 rounded-circle"
                                                                        style="width: 20px; height: 20px; font-size: 12px;"
                                                                        wire:click="removeExistingImage({{ $selectedProductIndex }}, {{ $img['id'] }})">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        @if (!empty($editOrders[$selectedProductIndex]['new_images']))
                                                            @foreach ($editOrders[$selectedProductIndex]['new_images'] as $idx => $tempImg)
                                                                <div class="image-item new position-relative"
                                                                    wire:key="new-img-{{ $idx }}-{{ $selectedProductIndex }}">
                                                                    <img src="{{ $tempImg->temporaryUrl() }}"
                                                                        class="rounded"
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                    <span
                                                                        class="badge-new position-absolute top-0 start-0 bg-success text-white small px-1"
                                                                        style="font-size: 10px;">جديد</span>
                                                                    <button type="button"
                                                                        class="btn-remove position-absolute top-0 end-0 bg-danger text-white border-0 rounded-circle"
                                                                        style="width: 20px; height: 20px; font-size: 12px;"
                                                                        wire:click="removeNewImage({{ $selectedProductIndex }}, {{ $idx }})">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <label
                                                            class="upload-box d-flex flex-column align-items-center justify-content-center border rounded p-2"
                                                            style="width: 80px; height: 80px; cursor: pointer;">
                                                            <input type="file" hidden multiple accept="image/*"
                                                                wire:model="editOrders.{{ $selectedProductIndex }}.temp_images"
                                                                wire:key="upload-{{ $selectedProductIndex }}-{{ time() }}">
                                                            <i class="fas fa-cloud-upload-alt text-primary mb-1"></i>
                                                            <span class="small text-muted"
                                                                style="font-size: 10px;">إضافة</span>
                                                        </label>
                                                    </div>
                                                    @error('editOrders.' . $selectedProductIndex . '.temp_images')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-mouse-pointer fa-3x mb-3 opacity-25"></i>
                                            <h5>اختر منتجاً من القائمة الجانبية</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-white border-top py-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary px-4" wire:click="closeEditModal">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </button>
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

        {{-- Driver Assignment Modal --}}
        @if ($showDriverModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-white"
                            style="background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                            <h5 class="modal-title">
                                <i class="fas fa-truck me-2"></i>
                                تعيين فواتير للسائق
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDriverModal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اختر السائق</label>
                                <select class="form-control" wire:model="selectedDriverId">
                                    <option value="">-- اختر السائق --</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedDriverId')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">سعر التوصيل</label>

                                <div class="input-group">
                                    <input type="number" class="form-control" wire:model="deliveryPrice"
                                        placeholder="أدخل سعر التوصيل" step="1000" list="delivery-prices">

                                    <span class="input-group-text bg-light">د.ع</span>
                                </div>

                                <datalist id="delivery-prices">
                                    <option value="3000">
                                    <option value="4000">
                                    <option value="5000">
                                </datalist>

                                @error('deliveryPrice')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                عدد الفواتير المحددة: <strong>{{ count($selectedInvoices) }}</strong>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" wire:click="closeDriverModal">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-success" wire:click="assignToDriver">
                                <i class="fas fa-check me-2"></i> تعيين
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Delete Product Confirmation Modal --}}
        @if ($showProductDeleteModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body text-center p-4">
                            <div class="mb-3 text-warning">
                                <i class="fas fa-exclamation-triangle fa-3x"></i>
                            </div>
                            <h5 class="mb-3">تأكيد حذف المنتج</h5>
                            <p class="text-muted mb-4">هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع عن هذا الإجراء.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-light"
                                    wire:click="closeProductDeleteModal">إلغاء</button>
                                <button type="button" class="btn btn-danger"
                                    wire:click="deleteProductFromEdit">حذف</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Invoice Modal --}}
        @if ($showDeleteModal)
            <div class="modal-backdrop fade show"></div>
            <div class="modal fade show d-block" tabindex="-1">
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
                                <button type="button" class="btn btn-danger px-4" wire:click="deleteInvoice">نعم،
                                    احذف</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <style>
            /* ===== FILTERS ===== */
            .filters-card {
                background: white;
                border-radius: 24px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .1);
                border: 1px solid #f1f5f9;
            }

            .filters-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 1rem;
                align-items: center;
            }

            .search-box-modern,
            .date-box-modern {
                position: relative;
            }

            .search-icon,
            .date-icon {
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

            /* ===== TABLE ===== */
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
                background: linear-gradient(135deg, #2264ce, #4208c8);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 20px;
            }


            .header-subtitle {
                font-size: 13px;
                color: #64748b;
                margin: 4px 0 0;
            }

            .badge-count-modern {
                background: #f1f5f9;
                color: #3b82f6;
                padding: 8px 16px;
                border-radius: 20px;
                font-weight: 600;
                font-size: 14px;
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
                font-size: 16px;
                text-align: center;
                border-bottom: 2px solid #e2e8f0;
            }

            .table-modern td {
                text-align: center;
                padding: 16px;
                color: #475569;
                font-size: 15px;
                border-bottom: 1px solid #f1f5f9;
            }

            .table-modern tr:hover td {
                background: #f8fafc;
            }

            .row-number {
                font-weight: 600;
                color: #3b82f6;
            }

            .invoice-badge {
                background: #e0f2fe;
                color: #0369a1;
                padding: 6px 12px;
                border-radius: 20px;
                font-weight: 600;
                font-size: 12px;
                font-family: monospace;
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

            .address-cell {
                display: flex;
                align-items: center;
                gap: 8px;
                max-width: 200px;
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

            .action-buttons {
                display: flex;
                gap: 8px;
                justify-content: center;
            }

            .btn-action {
                width: 35px;
                height: 35px;
                border: none;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all .2s;
            }

            .btn-action.edit {
                background: #dbeafe;
                color: #2563eb;
            }

            .btn-action:hover {
                transform: translateY(-2px);
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

            /* ===== MODAL STYLES ===== */
            .modal-backdrop {
                opacity: 0.5 !important;
            }

            .modal.show {
                margin-top: 50px;
                height: 90vh;
                display: block;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .active-product {
                background-color: #e0f2fe !important;
                border-right: 3px solid #4f46e5 !important;
            }

            .sidebar-column {
                width: 280px;
                background: white;
            }

            .content-column {
                background: #f8fafc;
            }

            /* Product Cards with White Background */
            .product-card-white {
                background-color: #ffffff !important;
                border-radius: 12px;
            }

            .product-card-white .card-body {
                background-color: #ffffff;
            }

            .x-small {
                font-size: 0.7rem;
            }

            .image-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 10px;
            }

            .image-item {
                position: relative;
                width: 80px;
                height: 80px;
            }

            .image-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 6px;
            }

            .btn-remove {
                position: absolute;
                top: -5px;
                right: -5px;
                width: 20px;
                height: 20px;
                background: #ef4444;
                color: white;
                border: none;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                cursor: pointer;
                transition: all .2s;
            }

            .btn-remove:hover {
                transform: scale(1.1);
                background: #dc2626;
            }

            .badge-new {
                position: absolute;
                top: 2px;
                left: 2px;
                background: #10b981;
                color: white;
                font-size: 8px;
                padding: 2px 4px;
                border-radius: 4px;
            }

            .upload-box {
                width: 80px;
                height: 80px;
                border: 2px dashed #cbd5e1;
                border-radius: 8px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all .2s;
                background: white;
            }

            .upload-box:hover {
                border-color: #4f46e5;
                background: #f8fafc;
            }

            .animate-fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* ===== RTL FIXES ===== */
            [dir="rtl"] .search-icon,
            [dir="rtl"] .date-icon {
                right: 16px;
                left: auto;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 992px) {
                .filters-grid {
                    grid-template-columns: 1fr;
                }

                .sidebar-column {
                    width: 240px;
                }
            }

            @media (max-width: 768px) {
                .modal-dialog {
                    margin: .5rem;
                }

                .sidebar-column {
                    display: none;
                }

                .content-column {
                    width: 100%;
                }

                .pagination-wrapper {
                    flex-direction: column;
                    gap: 1rem;
                    align-items: flex-start;
                }

                .table-modern {
                    font-size: 13px;
                }

                .table-modern th,
                .table-modern td {
                    padding: 12px 8px;
                }
            }

            .modal-close-btn {
                width: 30px;
                height: 30px;
                border-radius: 8px;
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
        </style>
    </div>
</div>
