<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login — Demo</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* small cosmetic tweaks */
    .card-width { max-width: 420px; width: 100%; }
    .brand { font-weight:700; letter-spacing: .4px; }
  </style>
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card card-width border border-2 border-primary rounded shadow-sm">
      <div class="card-body p-4">
        <h4 class="card-title mb-1 brand text-primary">YourApp</h4>
        <p class="text-muted mb-4">Welcome back — please login to your account</p>

        <form id="loginForm" action="<?= site_url('login') ?>" method="post" novalidate>
          <?= csrf_field() ?>
          <?php if (isset($login_error)): ?>
            <div class="alert alert-danger"><?= esc($login_error) ?></div>
          <?php endif; ?>
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email</label>
              <?php
                if (function_exists('set_value')) {
                  $emailVal = set_value('email');
                } elseif (isset($_REQUEST['email'])) {
                  $emailVal = esc($_REQUEST['email']);
                } else {
                  $emailVal = '';
                }
              ?>
              <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="loginEmail" name="email" value="<?= esc($emailVal) ?>" required>
            <?php if (isset($validation)): ?>
              <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
            <?php else: ?>
              <div class="invalid-feedback">Please enter a valid email.</div>
            <?php endif; ?>
            <div class="invalid-feedback">Please enter a valid email.</div>
          </div>

          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <div class="input-group">
              <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" id="loginPassword" name="password" minlength="8" required>
              <?php if (isset($validation)): ?>
                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
              <?php else: ?>
                <div class="invalid-feedback">Password must be at least 6 characters.</div>
              <?php endif; ?>
              <button class="btn btn-outline-secondary" type="button" id="toggleLoginPwd" aria-label="toggle password">Show</button>
              <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" />
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="#" class="small">Forgot password?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <hr class="my-3">

        <p class="text-center small mb-0">
          Don't have an account?
          <a href="/signup">Create free account</a>
        </p>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Temporary debug: log when login form submits
  (function(){
    var login = document.getElementById('loginForm');
    if (login) login.addEventListener('submit', function(e){
      e.preventDefault();
      console.log('loginForm submit intercepted - sending fetch');
      var form = e.currentTarget;
      var url = form.action || window.location.href;
      var data = new FormData(form);

      fetch(url, {
        method: 'POST',
        body: data,
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      }).then(function(res){
        console.log('fetch response', res.status, res.url, 'redirected=', res.redirected);
        if (res.redirected) {
          window.location = res.url;
          return;
        }
        var ct = res.headers.get('content-type') || '';
        if (ct.indexOf('application/json') !== -1) return res.json();
        return res.text();
      }).then(function(payload){
        if (!payload) return;
        if (typeof payload === 'string') {
          console.log('response body (truncated):', payload.substring(0,200));
          return;
        }

        // JSON response handling
        if (payload.redirect) {
          window.location = payload.redirect;
          return;
        }
        if (payload.errors) {
          console.log('validation errors:', payload.errors);
          return;
        }
        if (payload.message) {
          console.log('message:', payload.message);
          return;
        }
      }).catch(function(err){
        console.error('fetch error:', err);
        // fallback to normal submit
        try { form.submit(); } catch(e){}
      });
    });
  })();
</script>
</body>
</html>
