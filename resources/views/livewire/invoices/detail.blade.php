<div class="zenith-wrapper">
    @if($isOpen && $invoice)
        <!-- Light Background Overlay -->
        <div class="zenith-overlay" wire:click="close">
            <div class="light-particles"></div>
            <div class="light-orb orb-1"></div>
            <div class="light-orb orb-2"></div>
        </div>

        <!-- Main Container -->
        <div class="zenith-container">
            <div class="zenith-glass-card">
                
                <!-- Header -->
                <header class="zenith-header">
                    <div class="brand-group">
                        <div class="brand-logo">
                            <div class="logo-core"></div>
                        </div>
                        <div class="brand-text">
                            <span class="brand-subtitle">عرض الفاتورة</span>
                        </div>
                    </div>
                    
                    <div class="header-center">
                        <div class="invoice-badge">
                            <svg class="badge-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="#4f46e5"/>
                            </svg>
                            <span>رقم المرجع</span>
                            <span class="invoice-number">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                    
                    <button class="zenith-close-btn" wire:click="close" aria-label="إغلاق">
                        <svg class="close-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor"/>
                        </svg>
                    </button>
                </header>

                <!-- Main Content Grid - SWAPPED ORDER -->
                <div class="zenith-grid">
                    
                    <!-- تفاصيل الفاتورة on LEFT Side -->
                    <aside class="zenith-aside">
                        <div class="aside-scroll">
                            <!-- Invoice Data Section -->
                            <div class="section-block">
                                <h3 class="zenith-title">
                                    <svg class="title-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 19l7-7 3 3-7 7-3-3z" stroke="#4f46e5"/>
                                        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" stroke="#4f46e5"/>
                                        <path d="M2 2l7.586 7.586" stroke="#4f46e5"/>
                                        <circle cx="11" cy="11" r="2" stroke="#4f46e5"/>
                                    </svg>
                                    <span>تفاصيل الفاتورة</span>
                                </h3>
                                <div class="zenith-info-grid">
                                    <div class="zenith-tile">
                                        <div class="tile-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" stroke="#4f46e5"/>
                                                <circle cx="12" cy="10" r="3" stroke="#4f46e5"/>
                                            </svg>
                                        </div>
                                        <div class="tile-content">
                                            <label>الموقع</label>
                                            <span>{{ $invoice->address }}</span>
                                        </div>
                                    </div>
                                    <div class="zenith-tile">
                                        <div class="tile-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                                <path d="M16 3h5v5M17 16.92a7 7 0 01-10 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#4f46e5"/>
                                            </svg>
                                        </div>
                                        <div class="tile-content">
                                            <label>رقم المركبة</label>
                                            <span>{{ $invoice->truck_number }}</span>
                                        </div>
                                    </div>
                                    <div class="zenith-tile">
                                        <div class="tile-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="#4f46e5"/>
                                                <line x1="16" y1="2" x2="16" y2="6" stroke="#4f46e5"/>
                                                <line x1="8" y1="2" x2="8" y2="6" stroke="#4f46e5"/>
                                                <line x1="3" y1="10" x2="21" y2="10" stroke="#4f46e5"/>
                                            </svg>
                                        </div>
                                        <div class="tile-content">
                                            <label>تاريخ الفاتورة</label>
                                            <span>{{ $invoice->today_date->format('d M, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="zenith-tile">
                                        <div class="tile-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" stroke="#4f46e5"/>
                                            </svg>
                                        </div>
                                        <div class="tile-content">
                                            <label>رقم الهاتف</label>
                                            <span>{{ $invoice->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Items Section -->
                            <div class="section-block">
                                <div class="section-header">
                                    <h3 class="zenith-title">
                                        <svg class="title-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#4f46e5"/>
                                            <polyline points="14 2 14 8 20 8" stroke="#4f46e5"/>
                                            <line x1="16" y1="13" x2="8" y2="13" stroke="#4f46e5"/>
                                            <line x1="16" y1="17" x2="8" y2="17" stroke="#4f46e5"/>
                                            <polyline points="10 9 9 9 8 9" stroke="#4f46e5"/>
                                        </svg>
                                        <span>المنتجات</span>
                                    </h3>
                                    <div class="items-counter">
                                        <span>{{ count($items) }}</span>
                                        <span>عنصر</span>
                                    </div>
                                </div>
                                <div class="zenith-list">
                                    @foreach($items as $index => $item)
                                        <div class="zenith-item {{ $currentItemIndex == $index ? 'active' : '' }}" 
                                             wire:click="loadItemImages({{ $index }})">
                                            <div class="item-indicator">
                                                <div class="item-id">{{ sprintf('%02d', $index + 1) }}</div>
                                            </div>
                                            <div class="item-content">
                                                <div class="item-header">
                                                    <div class="name">{{ $item->name }}</div>
                                                    @if($item->link)
                                                    <div class="item-link-indicator">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="#06b6d4"/>
                                                            <polyline points="15 3 21 3 21 9" stroke="#06b6d4"/>
                                                            <line x1="10" y1="14" x2="21" y2="3" stroke="#06b6d4"/>
                                                        </svg>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="item-details">
                                                    <span class="qty">الكمية: {{ $item->quantity }}</span>
                                                    <span class="item-status">نشط</span>
                                                </div>
                                            </div>
                                            @if($currentItemIndex == $index)
                                                <div class="active-indicator">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                        <path d="M20 6L9 17l-5-5" stroke="#4f46e5" stroke-width="3"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if(isset($items[$currentItemIndex]) && $items[$currentItemIndex]->link)
                            <div class="zenith-footer">
                                <a href="{{ $items[$currentItemIndex]->link }}" target="_blank" class="zenith-action-btn">
                                    <div class="btn-content">
                                        <div class="btn-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="white"/>
                                                <polyline points="15 3 21 3 21 9" stroke="white"/>
                                                <line x1="10" y1="14" x2="21" y2="3" stroke="white"/>
                                            </svg>
                                        </div>
                                        <span>عرض المنتج الأصلي</span>
                                    </div>
                                    <span class="btn-arrow">→</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </aside>

                    <!-- معرض الصور on RIGHT Side -->
                    <main class="zenith-theater">
                        <div class="theater-header">
                            <h3 class="theater-title">
                                <svg class="title-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="#4f46e5"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5" stroke="#4f46e5"></circle>
                                    <polyline points="21 15 16 10 5 21" stroke="#4f46e5"></polyline>
                                </svg>
                                <span>معرض الصور</span>
                            </h3>
                            <div class="image-counter">
                                <span class="current-index">{{ $currentImageIndex + 1 }}</span>
                                <span class="counter-divider">/</span>
                                <span class="total-images">{{ count($images) }}</span>
                            </div>
                        </div>
                        
                        <div class="spotlight-box">
                            @if(count($images) > 0)
                                @php
                                    $img = $images[$currentImageIndex];
                                    $url = is_object($img) ? asset('storage/' . $img->image_path) : asset('storage/' . $img);
                                @endphp
                                
                                <div class="active-stage">
                                    <div class="image-frame">
                                        <img src="{{ $url }}" class="zenith-img-hero" alt="صورة الفاتورة">
                                    </div>
                                </div>

                                <!-- Thumbnail Gallery -->
                                <div class="thumbnail-gallery">
                                     <button class="zen-nav prev-btn" wire:click="prevImage" aria-label="الصورة السابقة">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                            <path d="M9 18l6-6-6-6" stroke="currentColor"/>
                                        </svg>
                                    </button>
                                          
                                    @foreach($images as $index => $image)
                                        @php
                                            $thumbUrl = is_object($image) ? asset('storage/' . $image->image_path) : asset('storage/' . $image);
                                        @endphp
                                        <div class="thumbnail-container {{ $currentImageIndex == $index ? 'active-thumb' : '' }}" 
                                             wire:click="selectImage({{ $index }})">
                                            <div class="thumbnail-frame">
                                                <img src="{{ $thumbUrl }}" class="thumbnail-img" alt="صورة مصغرة">
                                                @if($currentImageIndex == $index)
                                                    <div class="thumb-active-indicator"></div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <button class="zen-nav next-btn" wire:click="nextImage" aria-label="الصورة التالية">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                            <path d="M15 18l-6-6 6-6" stroke="currentColor"/>
                                        </svg>
                                    </button>
                                </div>

                                
                            @else
                                <div class="zenith-placeholder">
                                    <div class="placeholder-icon">
                                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" stroke="#94a3b8"/>
                                            <circle cx="12" cy="13" r="4" stroke="#94a3b8"/>
                                        </svg>
                                    </div>
                                    <p class="placeholder-text">لا توجد صور متاحة</p>
                                    <p class="placeholder-subtext">سيتم عرض الصور هنا عند توفرها</p>
                                </div>
                            @endif
                        </div>
                    </main>
                </div>
            </div>
        </div>
    @endif

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --zen-primary: #4f46e5;
        --zen-primary-light: #6366f1;
        --zen-secondary: #06b6d4;
        --zen-secondary-light: #22d3ee;
        --zen-accent: #8b5cf6;
        --zen-bg: #f8fafc;
        --zen-surface: #ffffff;
        --zen-card: #ffffff;
        --zen-border: #e2e8f0;
        --zen-border-light: #f1f5f9;
        --zen-text: #1e293b;
        --zen-text-light: #64748b;
        --zen-text-lighter: #94a3b8;
        --zen-gradient: linear-gradient(135deg, var(--zen-primary), var(--zen-secondary));
        --zen-gradient-light: linear-gradient(135deg, var(--zen-primary-light), var(--zen-secondary-light));
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .zenith-wrapper {
        direction: rtl;
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--zen-text);
        line-height: 1.6;
    }

    /* Light Background Overlay */
    .zenith-overlay {
        position: fixed;
        inset: 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        z-index: 9000;
        overflow: hidden;
    }

    .light-particles {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(79, 70, 229, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
        z-index: 1;
    }

    .light-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.3;
        animation: float 20s infinite alternate ease-in-out;
    }

    .orb-1 {
        width: 400px;
        height: 400px;
        background: var(--zen-primary);
        top: -100px;
        left: -100px;
        animation-delay: 0s;
    }

    .orb-2 {
        width: 300px;
        height: 300px;
        background: var(--zen-secondary);
        bottom: -50px;
        right: -50px;
        animation-delay: -10s;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, 30px) scale(1.1); }
    }

    /* Main Container */
    .zenith-container {
        position: fixed;
        inset: 0;
        z-index: 9001;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        pointer-events: none;
    }

    /* Glass Card with White Background */
    .zenith-glass-card {
        width: 100%;
        max-width: 1400px;
        height: 90vh;
        background: var(--zen-surface);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 24px;
        border: 1px solid var(--zen-border);
        box-shadow: var(--shadow-xl);
        pointer-events: auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Header */
    .zenith-header {
        padding: 1.25rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--zen-border);
        background: var(--zen-surface);
        position: relative;
        z-index: 10;
    }

    .brand-group {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .brand-logo {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-core {
        width: 16px;
        height: 16px;
        background: var(--zen-gradient);
        border-radius: 4px;
        position: relative;
        z-index: 2;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.125rem;
        color: var(--zen-text);
        letter-spacing: -0.5px;
    }

    .brand-subtitle {
        font-size: 0.75rem;
        color: var(--zen-text-light);
        font-weight: 500;
    }

    .invoice-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--zen-gradient);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        box-shadow: var(--shadow-md);
    }

    .invoice-number {
        font-weight: 800;
        font-family: 'Inter', sans-serif;
    }

    .zenith-close-btn {
        background: #f1f5f9;
        border: 1px solid var(--zen-border);
        color: var(--zen-text);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .zenith-close-btn:hover {
        background: #fee2e2;
        border-color: #ef4444;
        color: #dc2626;
        transform: rotate(90deg);
    }

 
    .zenith-grid {
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        flex: 1;
        overflow: hidden;
    }

    .zenith-aside {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        background: var(--zen-surface);
        position: relative;
        overflow: hidden;
        border-left: 1px solid var(--zen-border); 
    }

    .aside-scroll {
        height: 100%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding-right: 0.5rem; /* Changed from padding-left */
    }

    .aside-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .aside-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .aside-scroll::-webkit-scrollbar-thumb {
        background: var(--zen-primary);
        border-radius: 10px;
    }

    .zenith-theater {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        border-right: 1px solid var(--zen-border);
        position: relative;
        overflow: hidden;
    }

    .theater-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .theater-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--zen-text);
    }

    .title-icon {
        width: 20px;
        height: 20px;
    }

    .image-counter {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        background: white;
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        border: 1px solid var(--zen-border);
        box-shadow: var(--shadow-sm);
    }

    .current-index {
        color: var(--zen-primary);
        font-size: 1rem;
    }

    .counter-divider {
        color: var(--zen-text-lighter);
    }

    .total-images {
        color: var(--zen-text-light);
    }

    .spotlight-box {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
    }

    .active-stage {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }

    .image-frame {
        position: relative;
        width: 100%;
        max-width: 90%;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
        background: white;
        border: 1px solid var(--zen-border);
    }

    .zenith-img-hero {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: contain;
        display: block;
        padding: 1rem;
    }

    /* Thumbnail Gallery - SMALLER VERSION */
    .thumbnail-gallery {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 90%;
        margin: 0 auto;
        padding: 0.75rem;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--zen-border);
        box-shadow: var(--shadow-md);
    }

    .thumbnail-container {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 6px;
        overflow: hidden;
        width: 60px;
        height: 60px;
        flex-shrink: 0;
    }

    .thumbnail-container:hover {
        transform: translateY(-2px);
    }

    .thumbnail-container.active-thumb {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .thumbnail-frame {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 6px;
        overflow: hidden;
        border: 2px solid transparent;
        background: #f1f5f9;
    }

    .thumbnail-container.active-thumb .thumbnail-frame {
        border-color: var(--zen-primary);
    }

    .thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .thumbnail-container:hover .thumbnail-img {
        transform: scale(1.05);
    }

    .thumb-active-indicator {
        position: absolute;
        top: 3px;
        right: 3px;
        width: 10px;
        height: 10px;
        background: var(--zen-primary);
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: var(--shadow-sm);
    }

    /* Navigation buttons */
    .zen-nav {
        background: #f8fafc;
        border: 1px solid var(--zen-border);
        color: var(--zen-text);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .zen-nav:hover {
        background: var(--zen-primary);
        border-color: var(--zen-primary);
        color: white;
        transform: scale(1.05);
    }

    .zen-nav.prev-btn,
    .zen-nav.next-btn {
        margin: 0 0.25rem;
    }

    /* Placeholder */
    .zenith-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        gap: 1rem;
        text-align: center;
    }

    .placeholder-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 50%;
        border: 2px solid var(--zen-border);
        color: var(--zen-text-lighter);
    }

    .placeholder-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--zen-text);
    }

    .placeholder-subtext {
        font-size: 0.875rem;
        color: var(--zen-text-light);
        max-width: 300px;
    }

    .section-block {
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .zenith-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--zen-text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .items-counter {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        background: #f1f5f9;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--zen-text-light);
    }

    .items-counter span:first-child {
        color: var(--zen-primary);
        font-weight: 800;
    }

    /* Info Grid */
    .zenith-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }

    .zenith-tile {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid var(--zen-border);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .zenith-tile:hover {
        border-color: var(--zen-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .tile-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 8px;
        color: var(--zen-primary);
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .tile-content {
        flex: 1;
    }

    .zenith-tile label {
        display: block;
        font-size: 0.75rem;
        color: var(--zen-text-light);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .zenith-tile span {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--zen-text);
        display: block;
        line-height: 1.4;
    }

    /* Items List */
    .zenith-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .zenith-item {
        position: relative;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--zen-border);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .zenith-item:hover {
        border-color: var(--zen-primary);
        transform: translateX(-4px);
        box-shadow: var(--shadow-md);
    }

    .zenith-item.active {
        background: #f8fafc;
        border-color: var(--zen-primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    .item-indicator {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-id {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 0.875rem;
        color: var(--zen-text-light);
    }

    .zenith-item.active .item-id {
        color: var(--zen-primary);
    }

    .item-content {
        flex: 1;
        min-width: 0;
    }

    .item-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .name {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--zen-text);
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .item-link-indicator {
        color: var(--zen-secondary);
        flex-shrink: 0;
    }

    .item-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .qty {
        font-size: 0.8rem;
        color: var(--zen-text-light);
    }

    .item-status {
        font-size: 0.7rem;
        background: #10b981;
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .active-indicator {
        color: var(--zen-primary);
        flex-shrink: 0;
        background: #f1f5f9;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Footer Action Button */
    .zenith-footer {
        margin-top: auto;
        padding-top: 1.5rem;
        border-top: 1px solid var(--zen-border);
    }

    .zenith-action-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--zen-gradient);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        color: white;
        text-decoration: none;
        font-weight: 700;
        box-shadow: var(--shadow-lg);
        transition: all 0.2s ease;
    }

    .zenith-action-btn:hover {
        background: var(--zen-gradient-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .btn-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .btn-arrow {
        font-size: 1.25rem;
        font-weight: 300;
        transition: transform 0.2s ease;
    }

    .zenith-action-btn:hover .btn-arrow {
        transform: translateX(-4px);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .zenith-grid {
            grid-template-columns: 0.9fr 1.1fr; /* Adjusted for swapped order */
        }
    }

    @media (max-width: 1024px) {
        .zenith-grid {
            grid-template-columns: 1fr;
            overflow-y: auto;
        }
        
        .zenith-theater {
            height: 500px;
            min-height: 500px;
            border-right: none;
            border-bottom: 1px solid var(--zen-border);
        }
        
        .zenith-aside {
            border-left: none; /* Remove left border on mobile */
        }
        
        .zenith-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .thumbnail-container {
            width: 50px;
            height: 50px;
        }
        
        .zen-nav {
            width: 32px;
            height: 32px;
        }
    }

    @media (max-width: 768px) {
        .zenith-container {
            padding: 1rem;
        }
        
        .zenith-glass-card {
            height: 95vh;
            border-radius: 16px;
        }
        
        .zenith-header {
            padding: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .zenith-theater,
        .zenith-aside {
            padding: 1.5rem;
        }
        
        .zenith-info-grid {
            grid-template-columns: 1fr;
        }
        
        .thumbnail-gallery {
            max-width: 100%;
        }
        
        .thumbnail-container {
            width: 45px;
            height: 45px;
        }
        
        .zen-nav {
            width: 30px;
            height: 30px;
        }
        
        .thumbnail-gallery {
            gap: 0.375rem;
            padding: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .zenith-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .header-center {
            width: 100%;
            justify-content: space-between;
        }
        
        .zenith-close-btn {
            position: absolute;
            top: 1rem;
            left: 1rem;
        }
        
        .theater-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .thumbnail-container {
            width: 40px;
            height: 40px;
        }
        
        .zen-nav {
            width: 28px;
            height: 28px;
        }
        
        .thumbnail-gallery {
            gap: 0.25rem;
            padding: 0.375rem;
        }
        
        .zenith-item {
            padding: 0.75rem;
        }
    }
</style>
</div>