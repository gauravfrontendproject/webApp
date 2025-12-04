<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up — Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-width {
            max-width: 520px;
            width: 100%
        }

        .brand {
            font-weight: 700;
            letter-spacing: .4px
        }
    </style>
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card card-width border border-2 border-success rounded shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-1 brand text-success">Create your account</h4>
                <p class="text-muted mb-4">Sign up — it's quick and easy.</p>

                <!-- Use site_url() so URL generation is correct -->
                <form id="signupForm" action="<?= site_url('signup') ?>" method="post" novalidate>
                    <?= csrf_field() ?>

                    <?php if (isset($save_error)): ?>
                        <div class="alert alert-danger">
                            <strong>Save failed.</strong>
                            <?php if (! empty($save_error['model_errors'])): ?>
                                <div>Model errors:
                                    <ul>
                                        <?php foreach ($save_error['model_errors'] as $err): ?>
                                            <li><?= esc($err) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if (! empty($save_error['db_error']['message'])): ?>
                                <div>DB error: <?= esc($save_error['db_error']['message']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First name</label>
                            <?php $firstVal = (function_exists('set_value') ? set_value('firstname') : (isset($_REQUEST['firstname']) ? esc($_REQUEST['firstname']) : '')); ?>
                            <input type="text"
                                class="form-control <?= isset($validation) && $validation->hasError('firstname') ? 'is-invalid' : '' ?>"
                                id="firstName" name="firstname" value="<?= esc($firstVal) ?>" required>
                            <?php if (isset($validation)): ?>
                                <div class="invalid-feedback"><?= $validation->getError('firstname') ?></div>
                            <?php else: ?>
                                <div class="invalid-feedback">Required.</div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last name</label>
                            <?php $lastVal = (function_exists('set_value') ? set_value('lastname') : (isset($_REQUEST['lastname']) ? esc($_REQUEST['lastname']) : '')); ?>
                            <input type="text"
                                class="form-control <?= isset($validation) && $validation->hasError('lastname') ? 'is-invalid' : '' ?>"
                                id="lastName" name="lastname" value="<?= esc($lastVal) ?>" required>
                            <?php if (isset($validation)): ?>
                                <div class="invalid-feedback"><?= $validation->getError('lastname') ?></div>
                            <?php else: ?>
                                <div class="invalid-feedback">Required.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="signupEmail" class="form-label">Email</label>
                        <?php $emailVal = (function_exists('set_value') ? set_value('email') : (isset($_REQUEST['email']) ? esc($_REQUEST['email']) : '')); ?>
                        <input type="email"
                            class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                            id="signupEmail" name="email" value="<?= esc($emailVal) ?>" required>
                        <?php if (isset($validation)): ?>
                            <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                        <?php else: ?>
                            <div class="invalid-feedback">Please provide a valid email.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="signupPassword" class="form-label">Password</label>
                        <?php $pwdVal = function_exists('set_value') ? set_value('password') : ''; ?>
                        <input type="password" name="password"
                            class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>"
                            id="signupPassword" value="<?= esc($pwdVal) ?>" minlength="8" required>
                        <div class="form-text small">At least 8 characters.</div>
                        <?php if (isset($validation)): ?>
                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                        <?php else: ?>
                            <div class="invalid-feedback">Password must be at least 6 characters.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm password</label>
                        <?php $confirmVal = function_exists('set_value') ? set_value('confirmPassword') : ''; ?>
                        <input type="password" name="confirmPassword"
                            class="form-control <?= isset($validation) && $validation->hasError('confirmPassword') ? 'is-invalid' : '' ?>"
                            id="confirmPassword" value="<?= esc($confirmVal) ?>" minlength="8" required>
                        <?php if (isset($validation)): ?>
                            <div class="invalid-feedback"><?= $validation->getError('confirmPassword') ?></div>
                        <?php else: ?>
                            <div class="invalid-feedback">Passwords must match.</div>
                        <?php endif; ?>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">I agree to the Terms &amp; Privacy</label>
                        <div class="invalid-feedback">You must accept before submitting.</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Create account</button>
                </form>

                <hr class="my-3">
                <p class="text-center small mb-0">
                    Already have an account?
                    <a href="<?= site_url('login') ?>">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Temporary debug: log when forms are submitted so we can confirm the browser sends the POST
        (function(){
            var signup = document.getElementById('signupForm');
            if (signup) signup.addEventListener('submit', function(e){
                e.preventDefault();
                console.log('signupForm submit intercepted - sending fetch');
                var form = e.currentTarget;
                var url = form.action || window.location.href;
                var data = new FormData(form);

                fetch(url, { method: 'POST', body: data, credentials: 'same-origin' })
                .then(function(res){
                    console.log('fetch response', res.status, res.url, 'redirected=', res.redirected);
                    if (res.redirected) {
                        window.location = res.url;
                        return;
                    }
                    return res.text();
                }).then(function(text){
                    if (text) console.log('response body (truncated):', text.substring(0,200));
                }).catch(function(err){
                    console.error('fetch error:', err);
                    try { form.submit(); } catch(e){}
                });
            });

            var login = document.getElementById('loginForm');
            if (login) login.addEventListener('submit', function(e){
                try { console.log('loginForm submit triggered'); } catch(err){}
            });
        })();
    </script>
</body>

</html>