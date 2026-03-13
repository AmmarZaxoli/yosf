<div>
    <div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-12">

                <div class="text-center my-4">
                    <div class="header-container d-inline-block">
                        <h3 class="header-title mb-1">إنشاء فاتورة جديدة</h3>
                    </div>
                </div>

                <form wire:submit.prevent="saveAll">

                    {{-- SECTION 1: INVOICE INFO CARD --}}
                    <div class="section-card invoice-card mb-4">
                        <div class="section-card-header invoice-header">
                            <div class="section-icon invoice-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h5 class="section-title mb-0">بيانات الفاتورة</h5>
                                <small class="section-subtitle">معلومات العميل والفاتورة الأساسية</small>
                            </div>
                            <div class="ms-auto">
                                <span class="section-tag invoice-tag">Invoice Info</span>
                            </div>
                        </div>

                        <div class="section-card-body">
                            <div class="row g-3">
                                {{-- Invoice Number --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-hashtag me-1"></i> رقم الفاتورة
                                    </label>
                                    <div class="input-with-btn">
                                        <input type="text"
                                            class="field-input @error('invoice_number') is-invalid @enderror"
                                            wire:model="invoice_number" readonly placeholder="سيتم توليده تلقائياً">
                                        <button class="btn-inline invoice-btn" type="button" wire:click="addNewInvoice"
                                            @if ($invoice_button_disabled) disabled @endif>
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    @error('invoice_number')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Company Name --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-building me-1"></i> اسم الشركة
                                    </label>
                                    <input type="text"
class="field-input @error('namecompany') is-invalid @enderror"
wire:model.live="namecompany"
placeholder="اسم الشركة"
@if ($customer_fields_disabled) disabled @endif>
                                    @error('namecompany')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Customer Name --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-user me-1"></i> اسم العميل <span class="req">*</span>
                                    </label>
                                    <input type="text"
class="field-input @error('namecustomer') is-invalid @enderror"
wire:model="namecustomer"
placeholder="أدخل اسم العميل"
@if ($customer_fields_disabled) disabled @endif>
                                    @error('namecustomer')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-phone me-1"></i> رقم الموبايل <span class="req">*</span>
                                    </label>
                                    <input type="text" class="field-input @error('phone') is-invalid @enderror"
                                        wire:model="phone" placeholder="أدخل رقم الموبايل" @if ($customer_fields_disabled) disabled @endif>
                                    @error('phone')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-map-marker-alt me-1"></i> العنوان <span class="req">*</span>
                                    </label>
                                    <input type="text" class="field-input @error('address') is-invalid @enderror"
                                        wire:model="address" placeholder="أدخل العنوان الكامل" @if ($customer_fields_disabled) disabled @endif>
                                    @error('address')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Truck --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-truck me-1"></i> رقم تراك <span class="req">*</span>
                                    </label>
                                    <input type="text" class="field-input @error('id_truck') is-invalid @enderror"
                                        wire:model.live="id_truck" placeholder="أدخل رقم التراك" @if ($customer_fields_disabled) disabled @endif>
                                    @error('id_truck')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($customer_fields_disabled)
                                    <div class="col-12">
                                        <div class="lock-notice">
                                            <i class="fas fa-lock me-2"></i>
                                            تم تأمين بيانات العميل والشاحنة بعد إضافة أول منتج
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: PRODUCT INFO CARD --}}
                    <div class="section-card product-card mb-4">
                        <div class="section-card-header product-header">
                            <div class="section-icon product-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h5 class="section-title mb-0">بيانات المنتج</h5>
                                <small class="section-subtitle">أضف منتجاً واحداً أو أكثر إلى الفاتورة</small>
                            </div>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <span class="items-count-badge">{{ count($orders) }} منتج</span>
                                <span class="section-tag product-tag">Product Info</span>
                            </div>
                        </div>

                        <div class="section-card-body">
                            <div class="row g-3">
                                {{-- Link --}}
                                <div class="col-12">
                                    <label class="field-label">
                                        <i class="fas fa-link me-1"></i> رابط المنتج
                                        <span class="optional-tag">اختياري</span>
                                    </label>
                                    <div class="link-input-group">
                                        <input type="url" class="field-input link-field @error('link') is-invalid @enderror"
                                            wire:model.live="link" placeholder="https://example.com/product" id="link-input">
                                        <div class="link-button-container">
                                            <button type="button" class="btn-paste-link" onclick="pasteFromClipboard()" title="لصق من الحافظة">
                                                <i class="fas fa-paste"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('link')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Price --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-tag me-1"></i> سعر المنتج <span class="req">*</span>
                                    </label>
                                    <input type="number" step="250"
                                        class="field-input @error('productprice') is-invalid @enderror"
                                        wire:model.live="productprice" placeholder="0.00">
                                    @error('productprice')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Quantity --}}
                                <div class="col-md-4">
                                    <label class="field-label">الكمية <span class="req">*</span></label>
                                    <input type="number" class="field-input @error('quantity') is-invalid @enderror"
                                        wire:model.live="quantity" min="1" value="1">
                                    @error('quantity')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Images --}}
                                <div class="col-12">
                                    <label class="field-label">
                                        <i class="fas fa-images me-1"></i> صور المنتج
                                        <span class="badge-count">{{ count($images) }}/6</span>
                                    </label>

                                    <div class="image-upload-zone">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <input type="file" wire:model="temp_images" multiple
                                                    class="form-control @error('temp_images.*') is-invalid @enderror" 
                                                    @if (count($images) >= 6) disabled @endif
                                                    accept="image/*"
                                                    wire:loading.attr="disabled">
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    الحد الأقصى 6 صور، 2 ميجابايت لكل صورة
                                                </small>
                                                <div wire:loading wire:target="temp_images" class="mt-2">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                        <span class="visually-hidden">جاري التحميل...</span>
                                                    </div>
                                                    <small class="text-primary me-2">جاري معالجة الصور...</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                @if (count($images) > 0)
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="clearAllImages" wire:loading.attr="disabled">
                                                        <i class="fas fa-trash me-1"></i> مسح الكل
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        @error('temp_images.*')
                                            <div class="field-error mt-2"><i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}</div>
                                        @enderror

                                        @if (count($images) >= 6)
                                            <div class="alert alert-warning mt-2 mb-0 py-2">
                                                <i class="fas fa-exclamation-triangle me-1"></i> لقد وصلت إلى الحد الأقصى 6
                                                صور
                                            </div>
                                        @endif
                                    </div>

                                    @if (count($images) > 0)
                                        <div class="image-preview-grid mt-3">
                                            <small class="text-muted mb-2 d-block">الصور المضافة
                                                ({{ count($images) }}/6)</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($images as $index => $image)
                                                    <div class="preview-thumb preview-thumb-selected">
                                                        @if (is_string($image))
                                                            <img src="{{ asset('storage/' . $image) }}" class="thumb-img" loading="lazy">
                                                        @else
                                                            <img src="{{ $image->temporaryUrl() }}" class="thumb-img" loading="lazy">
                                                        @endif
                                                        <button type="button" class="thumb-remove"
                                                            wire:click="removeImage({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <span class="thumb-num">{{ $index + 1 }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Date Fields --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-calendar-day me-1"></i> تاريخ اليوم <span class="req">*</span>
                                    </label>
                                   <input type="date"
class="field-input @error('today_date') is-invalid @enderror"
wire:model.live="today_date"
@if ($customer_fields_disabled) disabled @endif>
                                    @error('today_date')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Delivery Days --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-clock me-1"></i> مدة التوصيل (أيام) <span class="req">*</span>
                                    </label>
                                   <div class="combined-input-group">

<input type="number"
class="field-input combined-field @error('delivery_days') is-invalid @enderror"
wire:model.live="delivery_days"
list="duration-options"
placeholder="اختر أو اكتب المدة"
min="1"
@if ($customer_fields_disabled) disabled @endif>

<datalist id="duration-options">
    <option value="15">15 يوم</option>
    <option value="18">18 يوم</option>
    <option value="20">20 يوم</option>
</datalist>

</div>
                                    <small class="text-muted">
                                        <i class="fas fa-edit me-1"></i> يمكنك الاختيار من القائمة أو كتابة الرقم يدوياً
                                    </small>
                                    @error('delivery_days')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Delivery Date --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="fas fa-truck me-1"></i> تاريخ التوصيل <span class="req">*</span>
                                    </label>
                                    <input type="date"
class="field-input @error('delivery_date') is-invalid @enderror"
wire:model="delivery_date"
readonly
@if ($customer_fields_disabled) disabled @endif>                         
                                    @error('delivery_date')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Add Product Button --}}
                                <div class="col-12 mt-2">
                                    <button type="button" class="btn-add-product" wire:click="validateAndAddOrder"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="validateAndAddOrder">
                                            <i class="fas fa-plus-circle me-2"></i> إضافة المنتج إلى الفاتورة
                                        </span>
                                        <span wire:loading wire:target="validateAndAddOrder">
                                            <i class="fas fa-spinner fa-spin me-2"></i> جاري الإضافة...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Orders Table --}}
                    @if (count($orders) > 0)
                        <div class="section-card orders-card mb-4">
                            <div class="section-card-header orders-header">
                                <div class="section-icon orders-icon">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div>
                                    <h5 class="section-title mb-0">عناصر الفاتورة</h5>
                                    <small class="section-subtitle">جميع المنتجات المضافة</small>
                                </div>
                                <div class="ms-auto d-flex align-items-center gap-2">
                                    <span class="section-tag orders-tag">{{ count($orders) }} عنصر</span>
                                    <button type="button" class="btn-clear-all" wire:click="clearAllOrders">
                                        <i class="fas fa-trash-alt me-1"></i> مسح الكل
                                    </button>
                                </div>
                            </div>

                            <div class="section-card-body p-0">
                                <div class="table-responsive">
                                    <table class="orders-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الرابط</th>
                                                <th>اسم الشركة</th>
                                                <th>الصور</th>
                                                <th>السعر</th>
                                                <th>الكمية</th>
                                                <th>الإجمالي</th>
                                                <th>مدة التوصيل</th>
                                                <th>تاريخ اليوم</th>
                                                <th>تاريخ التوصيل</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $index => $order)
                                                <tr>
                                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($order['link'] ?? null)
                                                            <a href="{{ $order['link'] }}" target="_blank" class="table-link">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-semibold">{{ $order['namecompany'] }}</td>
                                                    <td>
                                                        @if (count($order['images'] ?? []) > 0)
                                                            <div class="d-flex gap-1">
                                                                @foreach (array_slice($order['images'], 0, 3) as $image)
                                                                    @if (is_string($image))
                                                                        <img src="{{ asset('storage/' . $image) }}" class="table-thumb" loading="lazy">
                                                                    @else
                                                                        <img src="{{ $image->temporaryUrl() }}" class="table-thumb" loading="lazy">
                                                                    @endif
                                                                @endforeach
                                                                @if (count($order['images']) > 3)
                                                                    <span
                                                                        class="table-extra-imgs">+{{ count($order['images']) - 3 }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($order['price'], 2) }}</td>
                                                    <td>{{ $order['quantity'] }}</td>
                                                    <td class="fw-bold text-success">
                                                        {{ number_format($order['price'] * $order['quantity'], 2) }}</td>
                                                    <td>{{ $order['delivery_days'] ?? '-' }} يوم</td>
                                                    <td>{{ $order['today_date'] ?? now()->format('Y-m-d') }}</td>
                                                    <td>{{ $order['delivery_date'] ?? '-' }}</td>
                                                    <td>
                                                        <button type="button" class="btn-row-delete"
                                                            wire:click="removeOrder({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Summary Card --}}
                    <div class="section-card summary-card mb-4">
                        <div class="section-card-header summary-header">
                            <div class="section-icon summary-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <h5 class="section-title mb-0">ملخص الفاتورة</h5>
                                <small class="section-subtitle">المبلغ الإجمالي وإجراءات الحفظ</small>
                            </div>
                        </div>
                        <div class="section-card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label class="field-label">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        إجمالي الفاتورة <span class="req">*</span>
                                    </label>
                                    <input type="number" step="1"
                                        class="field-input total-field @error('total_price') is-invalid @enderror"
                                        wire:model="total_price" readonly>
                                    <small class="text-muted">
                                        <i class="fas fa-calculator me-1"></i> يتم الحساب تلقائياً من المنتجات
                                    </small>
                                    @error('total_price')
                                        <div class="field-error"><i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <div class="d-flex gap-3 justify-content-md-end mt-3">
                                        <button type="submit" class="btn-save" wire:loading.attr="disabled" @if (count($orders) === 0) disabled @endif>
                                            <span wire:loading.remove wire:target="saveAll">
                                                <i class="fas fa-save me-2"></i> حفظ الفاتورة
                                            </span>
                                            <span wire:loading wire:target="saveAll">
                                                <i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...
                                            </span>
                                        </button>
                                        <button type="button" class="btn-reset" wire:click="clearAll"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="clearAll">
                                                <i class="fas fa-trash-alt me-2"></i> مسح الكل
                                            </span>
                                            <span wire:loading wire:target="clearAll">
                                                <i class="fas fa-spinner fa-spin me-2"></i> جاري المسح...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for paste functionality --}}
<script>
    function pasteFromClipboard() {
        navigator.clipboard.readText().then(text => {
            @this.set('link', text);
        }).catch(err => {
            console.error('Failed to read clipboard contents: ', err);
            alert('تعذر الوصول إلى الحافظة. يرجى السماح بالوصول إلى الحافظة أو لصق الرابط يدوياً.');
        });
    }
</script>