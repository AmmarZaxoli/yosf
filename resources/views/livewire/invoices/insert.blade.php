<div class="container-fluid body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">إنشاء الطلبات</h5>
                </div>

                <div class="card-body">
                    <!-- Error Messages -->
                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Success Messages -->
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Invoice Number Section -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">رقم الفاتورة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                    wire:model="invoice_number" readonly placeholder=" إضافة الرقم">
                                <button class="btn btn-outline-primary" type="button" wire:click="addNewInvoice"
                                    @if($invoice_button_disabled) disabled @endif>
                                    <i class="fas fa-plus"></i> إضافة رقم
                                </button>
                            </div>
                            @error('invoice_number')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">المستخدم المسؤول <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <select class="form-select @error('selected_user') is-invalid @enderror"
                                    wire:model="selected_user" @if($customer_fields_disabled) disabled @endif>
                                    <option value="1">المستخدم الرئيسي</option>

                                    <!-- Your users options here -->
                                </select>
                            </div>
                            @error('selected_user')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- User Selection and Address -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">رقم الموبايل <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    wire:model="phone" placeholder="أدخل رقم الموبايل" @if($customer_fields_disabled)
                                    disabled @endif>
                            </div>
                            @error('phone')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">العنوان <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    wire:model="address" placeholder="أدخل العنوان الكامل"
                                    @if($customer_fields_disabled) disabled @endif>
                            </div>
                            @error('address')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">رقم تراك <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                <input type="text" class="form-control @error('truck_number') is-invalid @enderror"
                                    wire:model="truck_number" placeholder="أدخل رقم التراك"
                                    @if($customer_fields_disabled) disabled @endif>
                            </div>
                            @error('truck_number')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        @if($customer_fields_disabled)
                            <div class="col-12">
                                <small class="text-info d-block">
                                    <i class="fas fa-lock me-1"></i>
                                    تم تأمين بيانات العميل والشاحنة بعد إضافة أول منتج
                                </small>
                            </div>
                        @endif
                    </div>

                    <!-- Order Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">إضافة عنصر جديد للفاتورة</h5>
                            <span class="badge bg-info">{{ count($orders) }}</span>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Link -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">رابط المنتج (اختياري)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-text"></i></span>
                                        <input type="url" class="form-control @error('link') is-invalid @enderror"
                                            wire:model="link" placeholder="https://example.com/product">
                                    </div>
                                    @error('link')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            wire:model="name" placeholder="أدخل اسم المنتج">
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">صور المنتج <small class="text-muted">(الحد الأقصى 6
                                            صور)</small></label>

                                    <div class="d-flex align-items-start gap-3">
                                        <!-- File Input -->
                                        <div class="flex-grow-1">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                                <input type="file"
                                                    class="form-control @error('temp_images') is-invalid @enderror"
                                                    wire:model="temp_images" multiple accept="image/*"
                                                    @if(count($images) >= 6) disabled @endif>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                اختر صورة أو أكثر، الحد الأقصى 6 صور
                                                @if(count($images) > 0)
                                                    <span class="text-info fw-bold">
                                                        ({{ count($images) }}/6)
                                                    </span>
                                                @endif
                                            </small>
                                            @if(count($images) >= 6)
                                                <small class="text-danger d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    لقد وصلت إلى الحد الأقصى 6 صور
                                                </small>
                                            @endif
                                        </div>

                                        <!-- Clear Images Button -->
                                        @if(count($images) > 0)
                                            <button type="button" class="btn btn-outline-danger"
                                                wire:click="clearAllImages">
                                                <i class="fas fa-trash"></i> مسح كل الصور
                                            </button>
                                        @endif
                                    </div>
                                    @error('temp_images')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror

                                    <!-- Horizontal Image Preview -->
                                    @if(count($images) > 0)
                                        <div class="mt-3">
                                            <h6 class="mb-2">الصور المضافة ({{ count($images) }}/6)</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($images as $index => $image)
                                                    <div class="position-relative" style="width: 100px;">
                                                        @if(is_string($image))
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                class="img-fluid rounded border product-image"
                                                                style="width: 100px; height: 100px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ $image->temporaryUrl() }}"
                                                                class="img-fluid rounded border product-image"
                                                                style="width: 100px; height: 100px; object-fit: cover;">
                                                        @endif
                                                        <!-- Remove Button -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger position-absolute top-0 start-0"
                                                            style="transform: translate(-30%, -30%);"
                                                            wire:click="removeImage({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <!-- Image Number -->
                                                        <div
                                                            class="position-absolute bottom-0 start-50 translate-middle-x mb-1">
                                                            <span class="badge bg-dark rounded-pill">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quantity and Dates -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">الكمية <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                        <input type="number"
                                            class="form-control @error('quantity') is-invalid @enderror"
                                            wire:model="quantity" min="1">
                                    </div>
                                    @error('quantity')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">مدة التوصيل (أيام) <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="number"
                                            class="form-control @error('date_order') is-invalid @enderror"
                                            wire:model.live="date_order" min="1">
                                    </div>
                                    @error('date_order')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">تاريخ اليوم</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date"
                                            class="form-control @error('today_date') is-invalid @enderror"
                                            wire:model.live="today_date">
                                    </div>
                                    @error('today_date')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">تاريخ التوصيل المتوقع</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                        <input type="text" class="form-control bg-light text-success fw-bold"
                                            value="{{ $delivery_date }}" readonly style="cursor: default;">
                                    </div>
                                    @if($delivery_date)
                                        <small class="text-info mt-1 d-block">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $date_order }} يوم بعد {{ $today_date }}
                                        </small>
                                    @endif
                                </div>

                                <!-- Add Order Button -->
                                <div class="col-12 mt-3">
                                    <button class="btn btn-success w-100 py-2" wire:click="validateAndAddOrder"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="validateAndAddOrder">
                                            <i class="fas fa-plus-circle me-2"></i> إضافة المنتج إلى الفاتورة
                                        </span>
                                        <span wire:loading wire:target="validateAndAddOrder">
                                            <i class="fas fa-spinner fa-spin me-2"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    @if(count($orders) > 0)
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="background: linear-gradient(135deg, var(--success), #3aa8d8);">
                                <h5 class="mb-0 text-white">عناصر الفاتورة ({{ count($orders) }})</h5>
                                <button class="btn btn-sm btn-light" wire:click="clearAllOrders">
                                    <i class="fas fa-trash me-1"></i> مسح جميع العناصر
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="10%">الرابط</th>
                                                <th width="15%">الاسم</th>
                                                <th width="20%">الصور</th>
                                                <th width="8%">الكمية</th>
                                                <th width="10%">مدة التوصيل</th>
                                                <th width="12%">تاريخ اليوم</th>
                                                <th width="12%">تاريخ التوصيل</th>
                                                <th width="5%">إجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $index => $order)
                                                <tr>
                                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($order['link'])
                                                            <a href="{{ $order['link'] }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-bold">{{ $order['name'] }}</td>
                                                    <td>
                                                        @if(count($order['images']) > 0)
                                                            @foreach($order['images'] as $imageIndex => $image)
                                                                @if(is_string($image))
                                                                    <!-- Already saved image path -->
                                                                    <img src="{{ asset('storage/' . $image) }}"
                                                                        class="product-image img-thumbnail rounded"
                                                                        style="width:50px;height:50px;object-fit:cover;">
                                                                @else
                                                                    <!-- TemporaryUploadedFile object before saving -->
                                                                    <img src="{{ $image->temporaryUrl() }}"
                                                                        class="product-image img-thumbnail rounded"
                                                                        style="width:50px;height:50px;object-fit:cover;">
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary rounded-pill p-2">
                                                            {{ $order['quantity'] }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $order['date_order'] }} يوم</td>
                                                    <td>{{ $order['today_date'] }}</td>
                                                    <td class="fw-bold text-success">{{ $order['delivery_date'] }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-danger"
                                                            wire:click="removeOrder({{ $index }})" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="10" class="text-center fw-bold">
                                                    إجمالي العناصر: {{ count($orders) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-6 mb-2">
                            <button class="btn btn-danger w-100 py-2" wire:click="clearAll"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="clearAll">
                                    <i class="fas fa-trash-alt me-2"></i> مسح الكل
                                </span>
                                <span wire:loading wire:target="clearAll">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                </span>
                            </button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button class="btn btn-primary w-100 py-2" wire:click="saveAll" wire:loading.attr="disabled"
                                wire:target="saveAll" @if(count($orders) === 0) disabled @endif>
                                <span wire:loading.remove wire:target="saveAll">
                                    <i class="fas fa-save me-2"></i> حفظ الفاتورة
                                </span>
                                <span wire:loading wire:target="saveAll">
                                    <i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Flash Message -->
                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>