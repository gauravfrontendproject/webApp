<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <section class="Signup_section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="Signup_textsc">
                    
                        <!-- LEFT IMAGE -->
                        <div class="Signup_lefttxtbox">
                            <img src="<?= base_url('images/15.jpg') ?>" alt="Signup" class="Signup_img">
                        </div>

                        <!-- RIGHT FORM -->
                        <div class="Signup_righttxtbox">

                            <h3>Sign Up</h3>

                            <!-- SERVER ERROR -->
                            <?php if (isset($save_error)): ?>
                                <div class="alert alert-danger">
                                    <strong>Save failed.</strong>
                                    <?php if (!empty($save_error['model_errors'])): ?>
                                        <ul class="mb-0">
                                            <?php foreach ($save_error['model_errors'] as $err): ?>
                                                <li><?= esc($err) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- FORM -->
                            <form method="post" action="<?= site_url('signup') ?>" class="Signup_formsc">
                                <?= csrf_field() ?>

                                <!-- FIRST NAME -->
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" value="<?= set_value('firstname') ?>"
                                        placeholder="Enter First Name"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('firstname') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('firstname')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('firstname') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" value="<?= set_value('lastname') ?>"
                                        placeholder="Enter Last Name"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('lastname') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('lastname')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('lastname') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- EMAIL -->
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value="<?= set_value('email') ?>"
                                        placeholder="Enter Email"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('email') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- PHONE -->
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" value="<?= set_value('phone') ?>"
                                        placeholder="Enter Phone Number"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('phone') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('phone') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- PASSWORD -->
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" placeholder="Enter Password"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('password') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label>Confirm password</label>
                                    <input type="password" name="confirmPassword" placeholder="Enter Confirm Password"
                                        class="Signup_input <?= isset($validation) && $validation->hasError('confirmPassword') ? 'is-invalid' : '' ?>">
                                    <?php if (isset($validation) && $validation->hasError('confirmPassword')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('confirmPassword') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- SUBMIT -->
                                <button type="submit" class="Signup_formbtn mb-3">
                                    Sign Up
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>