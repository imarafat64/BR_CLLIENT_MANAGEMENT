<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="BR Client Management — Secure Portal Access">
    <title><?php echo e(config('app.name', 'BR Client Management')); ?> — <?php echo $__env->yieldContent('page_title', 'Portal'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Reset & Base ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #6c5ce7;
            --primary-light: #a29bfe;
            --primary-dark:  #4834d4;
            --accent:        #fd79a8;
            --accent2:       #00cec9;
            --surface:       #ffffff;
            --surface-2:     #f8f7ff;
            --text-primary:  #1a1a2e;
            --text-secondary:#6b7280;
            --text-muted:    #9ca3af;
            --border:        #e5e7eb;
            --border-focus:  #6c5ce7;
            --error:         #ef4444;
            --success:       #10b981;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     20px;
            --radius-xl:     28px;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md:     0 4px 16px rgba(108,92,231,.15);
            --shadow-lg:     0 20px 60px rgba(108,92,231,.18);
            --transition:    0.25s cubic-bezier(.4,0,.2,1);
        }

        html, body {
            height: 100%;
            min-height: 100vh;
        }

        body {
            font-family: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── Layout ───────────────────────────────────────── */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ── Left Panel ───────────────────────────────────── */
        .auth-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            overflow: hidden;
        }

        /* animated gradient orbs via ::before and ::after */
        .auth-panel::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(108,92,231,.45) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            animation: orbFloat 8s ease-in-out infinite alternate;
            pointer-events: none;
        }

        .auth-panel::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(253,121,168,.35) 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            animation: orbFloat 10s ease-in-out infinite alternate-reverse;
            pointer-events: none;
        }

        @keyframes orbFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 40px) scale(1.08); }
        }

        /* grid overlay */
        .auth-panel-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .auth-panel-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 420px;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 32px rgba(108,92,231,.4);
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%,100% { box-shadow: 0 8px 32px rgba(108,92,231,.4); }
            50%      { box-shadow: 0 8px 48px rgba(253,121,168,.5); }
        }

        .brand-logo svg { width: 38px; height: 38px; }

        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -.5px;
            margin-bottom: .5rem;
        }

        .brand-tagline {
            font-size: .9rem;
            color: rgba(255,255,255,.55);
            line-height: 1.6;
            margin-bottom: 3rem;
        }

        .panel-stats {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .stat-card {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius-md);
            padding: .9rem 1.4rem;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            background: rgba(255,255,255,.1);
            transform: translateY(-3px);
        }

        .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
        }

        .stat-label {
            font-size: .73rem;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-top: .15rem;
        }

        /* floating badges */
        .floating-badge {
            position: absolute;
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 100px;
            padding: .45rem 1rem;
            font-size: .78rem;
            color: rgba(255,255,255,.8);
            display: flex;
            align-items: center;
            gap: .5rem;
            animation: badgeFloat 6s ease-in-out infinite;
            z-index: 2;
        }

        .floating-badge.top    { top: 12%; left: 8%; animation-delay: 0s; }
        .floating-badge.middle { top: 45%; left: 5%; animation-delay: 2s; }
        .floating-badge.bottom { bottom: 18%; left: 6%; animation-delay: 4s; }

        .floating-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent2);
            box-shadow: 0 0 6px var(--accent2);
        }

        .floating-badge .dot.pink   { background: var(--accent); box-shadow: 0 0 6px var(--accent); }
        .floating-badge .dot.purple { background: var(--primary-light); box-shadow: 0 0 6px var(--primary-light); }

        @keyframes badgeFloat {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }

        /* ── Right Panel (Form area) ──────────────────────── */
        .auth-form-area {
            width: 480px;
            min-width: 420px;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Top-right decorative orb using ::before */
        .auth-form-area::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(108,92,231,.25) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Bottom-left decorative orb using ::after */
        .auth-form-area::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(253,121,168,.2) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .form-card {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 80px rgba(0,0,0,.35), 0 0 0 1px rgba(255,255,255,.15);
        }

        /* ── Form Header ──────────────────────────────────── */
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-eyebrow {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: var(--primary);
            margin-bottom: .5rem;
        }

        .form-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.5px;
            line-height: 1.2;
        }

        .form-subtitle {
            font-size: .9rem;
            color: var(--text-secondary);
            margin-top: .4rem;
        }

        /* ── Form Elements ────────────────────────────────── */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: .45rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            pointer-events: none;
            transition: color var(--transition);
        }

        .input-icon svg { width: 17px; height: 17px; }

        .form-input {
            width: 100%;
            padding: .75rem 1rem .75rem 2.75rem;
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            outline: none;
            -webkit-appearance: none;
        }

        .form-input::placeholder { color: var(--text-muted); }
        .form-input:hover { border-color: #c4b5fd; }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,92,231,.12);
            background: #fdfcff;
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
        }

        .form-input.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }

        /* password toggle */
        .input-action {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            padding: 2px;
            border-radius: 4px;
            transition: color var(--transition);
        }

        .input-action:hover { color: var(--primary); }
        .input-action svg { width: 17px; height: 17px; }

        /* password strength */
        .pw-strength {
            display: flex;
            gap: 4px;
            margin-top: .4rem;
        }

        .pw-strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 100px;
            background: var(--border);
            transition: background .3s ease;
        }

        .pw-strength-bar.active-weak   { background: #ef4444; }
        .pw-strength-bar.active-fair   { background: #f59e0b; }
        .pw-strength-bar.active-good   { background: #10b981; }
        .pw-strength-bar.active-strong { background: #6c5ce7; }

        /* invalid feedback */
        .invalid-feedback {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .78rem;
            color: var(--error);
            margin-top: .35rem;
        }

        .invalid-feedback::before {
            content: '⚠';
            font-size: .8em;
        }

        /* ── Checkbox ─────────────────────────────────────── */
        .check-group {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            margin: 1rem 0 1.4rem;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            min-width: 18px;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition);
            appearance: none;
            -webkit-appearance: none;
            margin-top: 1px;
        }

        .custom-checkbox:checked {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(108,92,231,.35);
        }

        .custom-checkbox:checked::after {
            content: '';
            width: 10px;
            height: 6px;
            border-left: 2px solid #fff;
            border-bottom: 2px solid #fff;
            transform: rotate(-45deg) translateY(-1px);
            display: block;
        }

        .check-label {
            font-size: .83rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .check-label a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color var(--transition);
        }

        .check-label a:hover { color: var(--primary-dark); text-decoration: underline; }

        /* ── Button ───────────────────────────────────────── */
        .btn-auth {
            width: 100%;
            padding: .85rem 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .01em;
            position: relative;
            overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 4px 20px rgba(108,92,231,.35);
        }

        .btn-auth::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15), transparent);
            opacity: 0;
            transition: opacity var(--transition);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(108,92,231,.45);
        }

        .btn-auth:hover::before { opacity: 1; }
        .btn-auth:active { transform: translateY(0); }

        /* loading spinner inside button */
        .btn-auth .btn-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            vertical-align: middle;
            margin-right: .5rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-auth.loading .btn-spinner { display: inline-block; }
        .btn-auth.loading .btn-text   { opacity: .8; }

        /* ── Divider ──────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.4rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            font-size: .78rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ── Footer link ──────────────────────────────────── */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .87rem;
            color: var(--text-secondary);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            transition: color var(--transition);
        }

        .auth-footer a:hover { color: var(--primary-dark); }

        .link-forgot {
            font-size: .82rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color var(--transition);
        }

        .link-forgot:hover { color: var(--primary-dark); text-decoration: underline; }

        /* ── Alert ────────────────────────────────────────── */
        .alert {
            padding: .75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        /* ── Responsive ───────────────────────────────────── */
        @media (max-width: 900px) {
            .auth-panel { display: none; }
            .auth-form-area {
                width: 100%;
                min-width: unset;
                padding: 2rem 1.5rem;
                border-left: none;
            }
            .form-card {
                box-shadow: 0 20px 60px rgba(0,0,0,.3);
            }
        }

        @media (max-width: 480px) {
            .auth-form-area { padding: 1.5rem 1rem; }
            .form-card { padding: 2rem 1.5rem; }
            .form-title { font-size: 1.5rem; }
        }

        /* ── Fade-in animation ────────────────────────────── */
        .fade-up {
            animation: fadeUp .5s cubic-bezier(.4,0,.2,1) both;
        }

        .fade-up:nth-child(1) { animation-delay: .05s; }
        .fade-up:nth-child(2) { animation-delay: .1s; }
        .fade-up:nth-child(3) { animation-delay: .15s; }
        .fade-up:nth-child(4) { animation-delay: .2s; }
        .fade-up:nth-child(5) { animation-delay: .25s; }
        .fade-up:nth-child(6) { animation-delay: .3s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">

    <!-- Left decorative panel -->
    <div class="auth-panel">
        <div class="auth-panel-grid"></div>

        <!-- Floating context badges -->
        <div class="floating-badge top">
            <span class="dot"></span>
            Active Clients: 1,240
        </div>
        <div class="floating-badge middle">
            <span class="dot pink"></span>
            Invoices This Month: 348
        </div>
        <div class="floating-badge bottom">
            <span class="dot purple"></span>
            Team Members: 24
        </div>

        <div class="auth-panel-content">
            <div class="brand-logo">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 12C8 9.79 9.79 8 12 8H28C30.21 8 32 9.79 32 12V20C32 26.63 26.63 32 20 32C13.37 32 8 26.63 8 20V12Z" fill="rgba(255,255,255,0.25)"/>
                    <path d="M14 18L18 22L26 14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="20" cy="8" r="3" fill="rgba(255,255,255,0.4)"/>
                </svg>
            </div>
            <div class="brand-name"><?php echo e(config('app.name', 'BR Client')); ?></div>
            <div class="brand-tagline">Your all-in-one client management portal.<br>Streamline operations, grow relationships.</div>

            <div class="panel-stats">
                <div class="stat-card">
                    <div class="stat-value">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">256-bit</div>
                    <div class="stat-label">Encryption</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right form area -->
    <div class="auth-form-area">
        <div class="form-card">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

</div>

<script>
    // Password visibility toggle
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.innerHTML = isText
            ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`
            : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
    }

    // Password strength meter
    function checkStrength(val, barsId) {
        const bars = document.querySelectorAll('#' + barsId + ' .pw-strength-bar');
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = ['', 'active-weak', 'active-fair', 'active-good', 'active-strong'];
        bars.forEach((bar, i) => {
            bar.className = 'pw-strength-bar';
            if (i < score) bar.classList.add(levels[score]);
        });
    }

    // Button loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('.btn-auth');
            if (btn) btn.classList.add('loading');
        });
    });
</script>
</body>
</html>
<?php /**PATH E:\BR_CLLIENT_MANAGEMENT\resources\views/layouts/auth.blade.php ENDPATH**/ ?>