<div>
    <div>
    <div class="invoice-page-header">
        <div class="title-pill">
            <i class="fas fa-file-alt"></i>
            إنشاء فاتورة جديدة
        </div>
        <p>أدخل بيانات العميل والمنتجات لإنشاء الفاتورة</p>
    </div>

    <form wire:submit.prevent="saveAll">

        {{-- SECTION 1: INVOICE INFO --}}
        <div class="form-card mb-4">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-file-alt fs-5"></i>
                    <div>
                        <h5 class="mb-0">بيانات الفاتورة</h5>
                        <small>معلومات العميل والفاتورة الأساسية</small>
                    </div>
                </div>
                <span class="badge bg-light text-dark ms-auto">Invoice Info</span>
            </div>

            <div class="p-4">
                <div class="invoice-grid cols-3">

                    {{-- Invoice Number --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-hashtag me-1"></i> رقم الفاتورة
                        </label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control @error('invoice_number') is-invalid @enderror"
                                wire:model="invoice_number" readonly placeholder="سيتم توليده تلقائياً">
                            <button class="btn btn-primary" type="button" wire:click="addNewInvoice"
                                @if($invoice_button_disabled) disabled @endif>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('invoice_number')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Company Name --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-building me-1"></i> اسم الشركة
                        </label>
                        <input type="text" list="company-options"
                            class="form-control @error('namecompany') is-invalid @enderror"
                            wire:model.live="namecompany" placeholder="اسم الشركة"
                            @if($customer_fields_disabled) disabled @endif>
                        <datalist id="company-options">
                            <option value="Shein">
                            <option value="Taobao">
                            <option value="Pinduoduo">
                            <option value="1688">
                        </datalist>
                        @error('namecompany')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Customer Name --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user me-1"></i> اسم العميل <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('namecustomer') is-invalid @enderror"
                            wire:model="namecustomer" placeholder="أدخل اسم العميل"
                            @if($customer_fields_disabled) disabled @endif>
                        @error('namecustomer')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-phone me-1"></i> رقم الموبايل <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('phone') is-invalid @enderror"
                            wire:model="phone" placeholder="07XXXXXXXXX"
                            @if($customer_fields_disabled) disabled @endif>
                        @error('phone')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i> العنوان <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('address') is-invalid @enderror"
                            wire:model="address" placeholder="أدخل العنوان الكامل"
                            @if($customer_fields_disabled) disabled @endif>
                        @error('address')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Truck --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-truck me-1"></i> رقم تراك <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('id_truck') is-invalid @enderror"
                            wire:model.live="id_truck" placeholder="أدخل رقم التراك"
                            @if($customer_fields_disabled) disabled @endif>
                        @error('id_truck')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    @if($customer_fields_disabled)
                        <div class="full-col">
                            <div class="lock-notice">
                                <i class="fas fa-lock"></i>
                                تم تأمين بيانات العميل والشاحنة بعد إضافة أول منتج
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- SECTION 2: PRODUCT INFO --}}
        <div class="form-card mb-4">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-box-open fs-5"></i>
                    <div>
                        <h5 class="mb-0">بيانات المنتج</h5>
                        <small>أضف منتجاً واحداً أو أكثر إلى الفاتورة</small>
                    </div>
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark">{{ count($orders) }} منتج</span>
                </div>
            </div>

            <div class="p-4">
                <div class="invoice-grid cols-1" style="gap:16px">

                    {{-- Link --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-link me-1"></i> رابط المنتج
                            <span class="img-count-badge ms-1">اختياري</span>
                        </label>
                       <div class="input-group">
    <input type="url"
        class="form-control @error('link') is-invalid @enderror"
        wire:model.live="link"
        placeholder="https://example.com/product"
        id="link-input">

    <button type="button"
        class="btn paste-btn"
        onclick="pasteFromClipboard()"
        title="لصق من الحافظة">
        <i class="fas fa-paste"></i>
    </button>
</div>
                        @error('link')
                            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price + Quantity --}}
                    <div class="invoice-grid cols-2">
                        <div>
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i> سعر المنتج <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="250"
                                class="form-control @error('productprice') is-invalid @enderror"
                                wire:model.live="productprice" placeholder="0">
                            @error('productprice')
                                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">الكمية <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control @error('quantity') is-invalid @enderror"
                                wire:model.live="quantity" min="1" value="1">
                            @error('quantity')
                                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Images --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-images me-1"></i> صور المنتج
                            <span class="img-count-badge ms-1">{{ count($images) }}/6</span>
                        </label>

                        <div class="upload-zone">
    <input type="file"
        wire:model.change="temp_images"
        multiple
        class="form-control @error('temp_images.*') is-invalid @enderror"
        @if(count($images) >= 6) disabled @endif
        accept="image/*"
        wire:loading.attr="disabled"
        wire:target="temp_images">

    <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap gap-2">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            الحد الأقصى 6 صور · 2 ميجابايت لكل صورة
        </small>

        @if(count($images) > 0)
            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearAllImages">
                <i class="fas fa-trash me-1"></i> مسح الكل
            </button>
        @endif
    </div>

    <div wire:loading.flex wire:target="temp_images" class="mt-2 align-items-center gap-2">
        <div class="spinner-border spinner-border-sm text-primary"></div>
        <small class="text-primary">جاري معالجة الصور...</small>
    </div>
</div>

                        @error('temp_images.*')
                            <div class="error-message mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror

                        @if(count($images) >= 6)
                            <div class="alert alert-warning mt-2 py-2 mb-0">
                                <i class="fas fa-exclamation-triangle me-1"></i> وصلت إلى الحد الأقصى 6 صور
                            </div>
                        @endif

                        @if(count($images) > 0)
                            <div class="thumb-grid mt-2">
                                @foreach($images as $index => $image)
                                    <div class="thumb-item">
                                        @if(is_string($image))
                                            <img src="{{ asset('storage/' . $image) }}" loading="lazy">
                                        @else
                                            <img src="{{ $image->temporaryUrl() }}" loading="lazy">
                                        @endif
                                        <button type="button" class="thumb-remove" wire:click="removeImage({{ $index }})">
                                            <i class="fas fa-times" style="font-size:9px"></i>
                                        </button>
                                        <span class="thumb-number">{{ $index + 1 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Date Fields --}}
                    <div class="invoice-grid cols-3">
                        <div>
                            <label class="form-label">
                                <i class="fas fa-calendar-day me-1"></i> تاريخ اليوم <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control @error('today_date') is-invalid @enderror"
                                wire:model.live="today_date"
                                @if($customer_fields_disabled) disabled @endif>
                            @error('today_date')
                                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i> مدة التوصيل (أيام) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control @error('delivery_days') is-invalid @enderror"
                                wire:model.live="delivery_days" list="duration-options"
                                placeholder="15" min="1"
                                @if($customer_fields_disabled) disabled @endif>
                            <datalist id="duration-options">
                                <option value="15">15 يوم</option>
                                <option value="18">18 يوم</option>
                                <option value="20">20 يوم</option>
                            </datalist>
                            <small class="text-muted" style="font-size:11px">يمكنك الاختيار أو الكتابة يدوياً</small>
                            @error('delivery_days')
                                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">
                                <i class="fas fa-truck me-1"></i> تاريخ التوصيل <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control @error('delivery_date') is-invalid @enderror"
                                wire:model="delivery_date" readonly
                                @if($customer_fields_disabled) disabled @endif>
                            @error('delivery_date')
                                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Add Product Button --}}
                    <button type="button" class="btn-add-product" wire:click="validateAndAddOrder" wire:loading.attr="disabled">
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

        {{-- SECTION 3: ORDERS TABLE --}}
        @if(count($orders) > 0)
            <div class="form-card mb-4">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-list-check fs-5"></i>
                        <div>
                            <h5 class="mb-0">عناصر الفاتورة</h5>
                            <small>جميع المنتجات المضافة</small>
                        </div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark">{{ count($orders) }} عنصر</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearAllOrders">
                            <i class="fas fa-trash-alt me-1"></i> مسح الكل
                        </button>
                    </div>
                </div>

                <div class="orders-table-wrap">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الرابط</th>
                                <th>الشركة</th>
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
                            @foreach($orders as $index => $order)
                                <tr>
                                    <td data-label="#">{{ $index + 1 }}</td>
                                    <td data-label="الرابط">
                                        @if($order['link'] ?? null)
                                            <a href="{{ $order['link'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td data-label="الشركة">{{ $order['namecompany'] }}</td>
                                    <td data-label="الصور">
                                        @if(count($order['images'] ?? []) > 0)
                                            <div class="thumb-grid" style="margin:0">
                                                @foreach(array_slice($order['images'], 0, 3) as $image)
                                                    <div class="thumb-item" style="width:36px;height:36px">
                                                        @if(is_string($image))
                                                            <img src="{{ asset('storage/' . $image) }}" loading="lazy">
                                                        @else
                                                            <img src="{{ $image->temporaryUrl() }}" loading="lazy">
                                                        @endif
                                                    </div>
                                                @endforeach
                                                @if(count($order['images']) > 3)
                                                    <span class="badge bg-secondary">+{{ count($order['images']) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td data-label="السعر">{{ number_format($order['price'], 0) }}</td>
                                    <td data-label="الكمية">{{ $order['quantity'] }}</td>
                                    <td data-label="الإجمالي" style="font-weight:700;color:var(--primary)">
                                        {{ number_format($order['price'] * $order['quantity'], 0) }}
                                    </td>
                                    <td data-label="مدة التوصيل">{{ $order['delivery_days'] ?? '—' }} يوم</td>
                                    <td data-label="تاريخ اليوم">{{ $order['today_date'] ?? now()->format('Y-m-d') }}</td>
                                    <td data-label="تاريخ التوصيل">{{ $order['delivery_date'] ?? '—' }}</td>
                                    <td>
                                        <button type="button" class="action-btn delete" wire:click="removeOrder({{ $index }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- SECTION 4: SUMMARY --}}
        <div class="form-card mb-4">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-receipt fs-5"></i>
                    <div>
                        <h5 class="mb-0">ملخص الفاتورة</h5>
                        <small>المبلغ الإجمالي وإجراءات الحفظ</small>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="invoice-grid cols-2" style="align-items:center">

                    <div class="summary-total-box">
                        <div class="total-label"><i class="fas fa-money-bill-wave me-1"></i> إجمالي الفاتورة</div>
                        <div class="total-value">{{ number_format($total_price, 0) }}</div>
                        @error('total_price')
                            <div class="error-message mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-sm-end">
                        <button type="submit" class="btn-save-invoice" wire:loading.attr="disabled"
                            @if(count($orders) === 0) disabled @endif>
                            <span wire:loading.remove wire:target="saveAll">
                                <i class="fas fa-save me-2"></i> حفظ الفاتورة
                            </span>
                            <span wire:loading wire:target="saveAll">
                                <i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...
                            </span>
                        </button>
                        <button type="button" class="btn-clear-invoice" wire:click="clearAll" wire:loading.attr="disabled">
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

    </form>
</div>

<script>
    function pasteFromClipboard() {
        navigator.clipboard.readText().then(text => {
            @this.set('link', text);
        }).catch(err => {
            alert('تعذر الوصول إلى الحافظة. يرجى السماح بالوصول أو لصق الرابط يدوياً.');
        });
    }
</script>

<style>
    /* Mobile table: card-style rows */
    @media (max-width: 640px) {
        .orders-table-wrap { overflow-x: unset; }
        .custom-table thead { display: none; }
        .custom-table tbody tr {
            display: block; margin-bottom: 12px;
            border: 1px solid var(--border); border-radius: 10px; padding: 12px;
        }
        .custom-table tbody td {
            display: flex; justify-content: space-between;
            align-items: center; padding: 6px 4px;
            border-bottom: 1px solid var(--border); font-size: 13px;
        }
        .custom-table tbody td:last-child { border-bottom: none; justify-content: flex-end; }
        .custom-table tbody td::before {
            content: attr(data-label);
            font-weight: 700; color: var(--text-secondary); font-size: 12px;
        }
    }
    .paste-btn {
    background: var(--primary);
    color: #fff;
    border: 1px solid var(--primary);
    border-radius: 10px;
    transition: var(--transition);
}

.paste-btn:hover {
    background: var(--primary-light);
    border-color: var(--primary-light);
    color: #fff;
}

</style>
</div>