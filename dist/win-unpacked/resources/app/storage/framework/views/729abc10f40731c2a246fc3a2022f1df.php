<?php $__env->startSection('page_title', 'Sign In'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-header fade-up">
    <div class="form-eyebrow">Manage Portal</div>
    <h1 class="form-title">Welcome back 👋</h1>
    <p class="form-subtitle">Sign in to access your dashboard</p>
</div>


<?php if(session('status')): ?>
    <div class="alert alert-success fade-up" role="alert">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-error fade-up" role="alert">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('login')); ?>" id="login-form" novalidate>
    <?php echo csrf_field(); ?>

    
    <div class="form-group fade-up">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </span>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="name@example.com"
                value="<?php echo e(old('email')); ?>"
                required
                autocomplete="email"
                autofocus
            >
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="form-group fade-up">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <label for="password" class="form-label" style="margin-bottom:0;">Password</label>
            <?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="link-forgot">Forgot password?</a>
            <?php endif; ?>
        </div>
        <div class="input-wrapper" style="margin-top:.45rem;">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="Enter your password"
                required
                autocomplete="current-password"
            >
            <button type="button" class="input-action" id="toggle-pw-login" onclick="togglePassword('password', this)" aria-label="Show/hide password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" role="alert"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="check-group fade-up">
        <input
            class="custom-checkbox"
            type="checkbox"
            name="remember"
            id="remember"
            <?php echo e(old('remember') ? 'checked' : ''); ?>

        >
        <label class="check-label" for="remember">Keep me signed in for 30 days</label>
    </div>

    
    <div class="fade-up">
        <button type="submit" id="login-btn" class="btn-auth">
            <span class="btn-spinner"></span>
            <span class="btn-text">Sign In</span>
        </button>
    </div>
</form>

<div class="auth-footer fade-up">
    Don't have an account?
    <?php if(Route::has('register')): ?>
        <a href="<?php echo e(route('register')); ?>">Create one free</a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\BR_CLLIENT_MANAGEMENT\resources\views/auth/login.blade.php ENDPATH**/ ?>