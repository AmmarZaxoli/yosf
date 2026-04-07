<div x-data="{ showPassword: false }">
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>نظام نقطة البيع - تسجيل الدخول</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @livewireStyles

        <style>
            * { font-family: 'Tajawal', sans-serif; box-sizing: border-box; }

            :root {
                --gold: #C9A84C;
                --gold-light: #E8C96E;
                --gold-dark: #9A7A2F;
                --dark: #0A0A0F;
                --dark-2: #111118;
                --dark-3: #181820;
                --dark-4: #1E1E28;
                --dark-5: #252530;
            }

            body {
                background-color: var(--dark);
                min-height: 100vh;
                overflow-x: hidden;
                position: relative;
            }

            .geo-bg {
                position: fixed;
                inset: 0;
                overflow: hidden;
                pointer-events: none;
                z-index: 0;
            }

            .geo-bg::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    repeating-linear-gradient(
                        45deg,
                        transparent,
                        transparent 60px,
                        rgba(201,168,76,0.03) 60px,
                        rgba(201,168,76,0.03) 61px
                    ),
                    repeating-linear-gradient(
                        -45deg,
                        transparent,
                        transparent 60px,
                        rgba(201,168,76,0.03) 60px,
                        rgba(201,168,76,0.03) 61px
                    );
            }

            .geo-circle {
                position: absolute;
                border-radius: 50%;
                border: 1px solid rgba(201,168,76,0.08);
            }

            .geo-circle-1 { width: 700px; height: 700px; top: -200px; left: -200px; }
            .geo-circle-2 { width: 500px; height: 500px; bottom: -150px; right: -100px; }
            .geo-circle-3 { width: 300px; height: 300px; top: 40%; left: 40%; margin: -150px; border-color: rgba(201,168,76,0.05); }

            .glow-1 {
                position: absolute;
                width: 600px;
                height: 600px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(201,168,76,0.06) 0%, transparent 70%);
                top: -100px;
                left: -200px;
                animation: pulse-glow 8s ease-in-out infinite;
            }

            .glow-2 {
                position: absolute;
                width: 500px;
                height: 500px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(100,80,200,0.07) 0%, transparent 70%);
                bottom: -100px;
                right: -100px;
                animation: pulse-glow 8s ease-in-out infinite 4s;
            }

            @keyframes pulse-glow {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.6; transform: scale(1.1); }
            }

            .stars {
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 0;
            }

            .star {
                position: absolute;
                background: var(--gold);
                border-radius: 50%;
                animation: twinkle var(--dur) ease-in-out infinite var(--delay);
            }

            @keyframes twinkle {
                0%, 100% { opacity: 0; transform: scale(0.5); }
                50% { opacity: 1; transform: scale(1); }
            }

            .page-wrapper {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.5rem;
            }

            .login-grid {
                width: 100%;
                max-width: 1100px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
            }

            @media (max-width: 900px) {
                .login-grid { grid-template-columns: 1fr; gap: 2rem; }
                .brand-side { order: 1; }
                .form-side { order: 0; }
            }

            .brand-side {
                color: #fff;
                animation: fadeSlideRight 0.9s cubic-bezier(.22,1,.36,1) both;
            }

            @keyframes fadeSlideRight {
                from { opacity: 0; transform: translateX(-60px); }
                to { opacity: 1; transform: translateX(0); }
            }

            .logo-badge {
                display: inline-flex;
                align-items: center;
                gap: 1rem;
                background: rgba(201,168,76,0.08);
                border: 1px solid rgba(201,168,76,0.2);
                border-radius: 16px;
                padding: 1rem 1.5rem;
                margin-bottom: 2.5rem;
                backdrop-filter: blur(20px);
            }

            .logo-icon {
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, var(--gold-dark), var(--gold-light));
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: var(--dark);
                box-shadow: 0 0 24px rgba(201,168,76,0.3);
            }

            .logo-text-main {
                font-size: 1.3rem;
                font-weight: 800;
                color: #fff;
            }

            .logo-text-sub {
                font-size: 0.75rem;
                color: rgba(201,168,76,0.8);
                font-weight: 400;
                margin-top: 2px;
            }

            .brand-headline {
                font-family: 'Amiri', serif;
                font-size: clamp(2.5rem, 4vw, 3.8rem);
                line-height: 1.2;
                margin-bottom: 1.25rem;
                color: #fff;
            }

            .brand-headline span {
                background: linear-gradient(135deg, var(--gold-dark), var(--gold-light), var(--gold-dark));
                background-size: 200%;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: shimmer 4s linear infinite;
            }

            @keyframes shimmer {
                from { background-position: 0% center; }
                to { background-position: 200% center; }
            }

            .brand-desc {
                font-size: 1rem;
                color: rgba(255,255,255,0.6);
                line-height: 1.8;
                margin-bottom: 2.5rem;
            }

            .feature-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .feature-card {
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(201,168,76,0.12);
                border-radius: 14px;
                padding: 1.2rem;
                display: flex;
                align-items: center;
                gap: 0.9rem;
                transition: all 0.3s ease;
            }

            .feature-card:hover {
                background: rgba(201,168,76,0.06);
                border-color: rgba(201,168,76,0.25);
                transform: translateY(-2px);
            }

            .feature-icon {
                width: 42px;
                height: 42px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .feature-label {
                font-size: 0.9rem;
                font-weight: 600;
                color: rgba(255,255,255,0.85);
            }

            .feature-sublabel {
                font-size: 0.72rem;
                color: rgba(255,255,255,0.4);
                margin-top: 2px;
            }

            .form-side {
                animation: fadeSlideLeft 0.9s cubic-bezier(.22,1,.36,1) 0.15s both;
            }

            @keyframes fadeSlideLeft {
                from { opacity: 0; transform: translateX(60px); }
                to { opacity: 1; transform: translateX(0); }
            }

            .form-card {
                background: var(--dark-3);
                border: 1px solid rgba(201,168,76,0.15);
                border-radius: 28px;
                overflow: hidden;
                box-shadow:
                    0 40px 80px rgba(0,0,0,0.5),
                    0 0 0 1px rgba(255,255,255,0.03),
                    inset 0 1px 0 rgba(255,255,255,0.05);
                position: relative;
            }

            .form-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 10%;
                right: 10%;
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--gold-light), transparent);
                opacity: 0.5;
            }

            .card-header {
                background: linear-gradient(135deg, rgba(201,168,76,0.12), rgba(201,168,76,0.04));
                border-bottom: 1px solid rgba(201,168,76,0.1);
                padding: 2.5rem 2rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .card-header::after {
                content: '';
                position: absolute;
                inset: 0;
                background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23C9A84C' fill-opacity='0.04'%3E%3Cpath d='M20 0l5 10 10 5-10 5-5 10-5-10L0 15l10-5z'/%3E%3C/g%3E%3C/svg%3E");
            }

            .header-icon-wrap {
                position: relative;
                z-index: 1;
                width: 72px;
                height: 72px;
                margin: 0 auto 1.25rem;
            }

            .header-icon-ring {
                position: absolute;
                inset: -8px;
                border-radius: 50%;
                border: 1px solid rgba(201,168,76,0.3);
                animation: spin-slow 12s linear infinite;
            }

            .header-icon-ring::before {
                content: '';
                position: absolute;
                width: 8px;
                height: 8px;
                background: var(--gold);
                border-radius: 50%;
                top: 50%;
                left: -4px;
                margin-top: -4px;
            }

            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .header-icon {
                width: 72px;
                height: 72px;
                background: linear-gradient(135deg, var(--gold-dark), var(--gold-light));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                color: var(--dark);
                box-shadow: 0 0 32px rgba(201,168,76,0.4), 0 0 80px rgba(201,168,76,0.15);
                position: relative;
                z-index: 1;
            }

            .card-title {
                font-family: 'Amiri', serif;
                font-size: 1.6rem;
                color: #fff;
                position: relative;
                z-index: 1;
                margin-bottom: 0.25rem;
            }

            .card-subtitle {
                font-size: 0.85rem;
                color: rgba(201,168,76,0.7);
                position: relative;
                z-index: 1;
            }

            .form-body {
                padding: 2rem 2rem 2.5rem;
            }

            .error-alert {
                background: rgba(220,50,50,0.08);
                border: 1px solid rgba(220,50,50,0.25);
                border-radius: 12px;
                padding: 0.875rem 1rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                animation: shake 0.4s ease;
            }

            .error-alert i { color: #ff6b6b; font-size: 1rem; }
            .error-alert p { color: #ff9090; font-size: 0.875rem; font-weight: 500; margin: 0; }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                20% { transform: translateX(-8px); }
                40% { transform: translateX(8px); }
                60% { transform: translateX(-5px); }
                80% { transform: translateX(5px); }
            }

            .field-group {
                margin-bottom: 1.5rem;
            }

            .field-label {
                display: block;
                font-size: 0.82rem;
                font-weight: 600;
                color: rgba(255,255,255,0.6);
                margin-bottom: 0.6rem;
                letter-spacing: 0.03em;
                text-transform: uppercase;
            }

            .field-label i {
                color: var(--gold);
                margin-left: 0.4rem;
            }

            .input-wrap {
                position: relative;
            }

            .form-input,
            .form-select {
                width: 100%;
                background: var(--dark-5);
                border: 1px solid rgba(201,168,76,0.15);
                border-radius: 14px;
                padding: 0.875rem 1.25rem;
                color: #fff;
                font-family: 'Tajawal', sans-serif;
                font-size: 0.95rem;
                outline: none;
                transition: all 0.3s ease;
                appearance: none;
                -webkit-appearance: none;
            }

            .form-input::placeholder {
                color: rgba(255,255,255,0.25);
            }

            .form-input:focus,
            .form-select:focus {
                border-color: var(--gold);
                background: rgba(201,168,76,0.05);
                box-shadow: 0 0 0 3px rgba(201,168,76,0.1), 0 0 20px rgba(201,168,76,0.08);
            }

            .form-select {
                cursor: pointer;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23C9A84C'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: left 1rem center;
                background-size: 1.2rem;
                padding-left: 3rem;
            }

            .form-select option {
                background: var(--dark-3);
                color: #fff;
            }

            .toggle-password {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: var(--gold);
                cursor: pointer;
                font-size: 1rem;
                padding: 0;
                transition: color 0.2s;
            }

            .toggle-password:hover {
                color: var(--gold-light);
            }

            .field-error {
                font-size: 0.75rem;
                color: #ff8080;
                margin-top: 0.4rem;
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .btn-submit {
                width: 100%;
                background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-light) 50%, var(--gold-dark) 100%);
                background-size: 200%;
                border: none;
                border-radius: 14px;
                padding: 1rem;
                color: var(--dark);
                font-family: 'Tajawal', sans-serif;
                font-size: 1rem;
                font-weight: 800;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                transition: all 0.4s ease;
                box-shadow: 0 4px 20px rgba(201,168,76,0.25), 0 1px 0 rgba(255,255,255,0.2) inset;
                position: relative;
                overflow: hidden;
            }

            .btn-submit::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
                transform: translateX(-100%);
                transition: transform 0.6s ease;
            }

            .btn-submit:hover::before {
                transform: translateX(100%);
            }

            .btn-submit:hover {
                background-position: 100%;
                transform: translateY(-2px);
                box-shadow: 0 8px 32px rgba(201,168,76,0.4);
            }

            .btn-submit:active {
                transform: translateY(0);
            }

            .btn-submit:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                transform: none;
            }

            .divider {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin: 1.75rem 0 1.25rem;
            }

            .divider-line {
                flex: 1;
                height: 1px;
                background: rgba(201,168,76,0.1);
            }

            .divider-text {
                font-size: 0.72rem;
                color: rgba(255,255,255,0.25);
                white-space: nowrap;
                letter-spacing: 0.05em;
            }

            .security-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                margin-top: 1.5rem;
                font-size: 0.72rem;
                color: rgba(255,255,255,0.2);
            }

            .security-badge i {
                color: rgba(201,168,76,0.4);
            }

            .ornament {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 2rem;
            }

            .ornament-line {
                flex: 1;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(201,168,76,0.3));
            }

            .ornament-diamond {
                width: 6px;
                height: 6px;
                background: var(--gold);
                transform: rotate(45deg);
                opacity: 0.6;
            }
        </style>
    </head>
    <body>
        <div class="geo-bg">
            <div class="geo-circle geo-circle-1"></div>
            <div class="geo-circle geo-circle-2"></div>
            <div class="geo-circle geo-circle-3"></div>
            <div class="glow-1"></div>
            <div class="glow-2"></div>
        </div>

        <div class="stars" id="stars"></div>

        <div class="page-wrapper">
            <div class="login-grid">
                <div class="brand-side">
                    <div class="logo-badge">
                        <div class="logo-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <div class="logo-text-main">نظام نقطة البيع</div>
                            <div class="logo-text-sub">Premium Point of Sale</div>
                        </div>
                    </div>

                    <div class="ornament" style="flex-direction: row-reverse;">
                        <div class="ornament-line" style="background: linear-gradient(90deg, rgba(201,168,76,0.3), transparent);"></div>
                        <div class="ornament-diamond"></div>
                        <div class="ornament-diamond" style="opacity:0.3;"></div>
                    </div>

                    <h1 class="brand-headline">
                        أهلاً بك في<br>
                        <span>نظام البيع </span>
                    </h1>

                    <p class="brand-desc">
                        منصة متكاملة لإدارة المبيعات والمخزون بكفاءة عالية وسرعة فائقة. ابدأ رحلتك نحو إدارة أعمال أكثر ذكاءً.
                    </p>

                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon" style="background: rgba(201,168,76,0.12);">
                                <i class="fas fa-chart-line" style="color: var(--gold);"></i>
                            </div>
                            <div>
                                <div class="feature-label">تحليلات فورية</div>
                                <div class="feature-sublabel">لحظة بلحظة</div>
                            </div>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon" style="background: rgba(50,200,120,0.1);">
                                <i class="fas fa-boxes" style="color: #4ade80;"></i>
                            </div>
                            <div>
                                <div class="feature-label">إدارة المخزون</div>
                                <div class="feature-sublabel">تتبع ذكي</div>
                            </div>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon" style="background: rgba(100,150,255,0.1);">
                                <i class="fas fa-users" style="color: #818cf8;"></i>
                            </div>
                            <div>
                                <div class="feature-label">متعدد المستخدمين</div>
                                <div class="feature-sublabel">صلاحيات مرنة</div>
                            </div>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon" style="background: rgba(240,80,80,0.1);">
                                <i class="fas fa-shield-alt" style="color: #f87171;"></i>
                            </div>
                            <div>
                                <div class="feature-label">دفع آمن</div>
                                <div class="feature-sublabel">تشفير كامل</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-side">
                    <div class="form-card">
                        <div class="card-header">
                            <div class="header-icon-wrap">
                                <div class="header-icon-ring"></div>
                                <div class="header-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            <h2 class="card-title">تسجيل الدخول</h2>
                            <p class="card-subtitle">مرحباً بك، يُرجى إدخال بياناتك</p>
                        </div>

                        <div class="form-body">
                            @if($errorMessage)
                                <div class="error-alert">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <p>{{ $errorMessage }}</p>
                                </div>
                            @endif

                            <form wire:submit.prevent="login">
                                <div class="field-group">
                                    <label class="field-label">
                                        <i class="fas fa-user-circle"></i>
                                        اسم المستخدم
                                    </label>
                                    <div class="input-wrap">
                                        <select wire:model="name" class="form-select">
                                            <option value="">— اختر اسم المستخدم —</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->name }}">
                                                    {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('name')
                                        <p class="field-error">
                                            <i class="fas fa-times-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        <i class="fas fa-key"></i>
                                        كلمة المرور
                                    </label>

                                    <div class="input-wrap">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            wire:model.defer="password"
                                            class="form-input"
                                            placeholder="أدخل كلمة المرور"
                                            style="padding-left: 3rem;"
                                        >

                                        <button
                                            type="button"
                                            @click="showPassword = !showPassword"
                                            class="toggle-password"
                                        >
                                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </button>
                                    </div>

                                    @error('password')
                                        <p class="field-error">
                                            <i class="fas fa-times-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit" wire:loading.attr="disabled" class="btn-submit">
                                    <span wire:loading.remove class="flex items-center gap-2">
                                        <i class="fas fa-sign-in-alt"></i>
                                        دخول
                                    </span>
                                    <span wire:loading class="flex items-center gap-2">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        جارٍ تسجيل الدخول...
                                    </span>
                                </button>
                            </form>

                            <div class="divider">
                                <div class="divider-line"></div>
                                <span class="divider-text">حسابات تجريبية</span>
                                <div class="divider-line" style="background: linear-gradient(90deg, rgba(201,168,76,0.1), transparent);"></div>
                            </div>

                            <div class="security-badge">
                                <i class="fas fa-shield-alt"></i>
                                <span>اتصال آمن ومشفر بالكامل</span>
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @livewireScripts

        <script>
            const starsContainer = document.getElementById('stars');

            for (let i = 0; i < 60; i++) {
                const star = document.createElement('div');
                const size = Math.random() * 2.5 + 0.5;

                star.className = 'star';
                star.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    --dur: ${Math.random() * 4 + 2}s;
                    --delay: ${Math.random() * 6}s;
                `;

                starsContainer.appendChild(star);
            }
        </script>
    </body>
    </html>
</div>