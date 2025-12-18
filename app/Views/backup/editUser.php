<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit — User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container martop">
        <div class="col-md-6">
            <h4>Update User</h4>
            <?php if (isset($flash_mesage)): ?>
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        Congratulations! Updated Successfully.
                    </div>
                </div>
            <?php endif; ?>
            <form method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="fullname">
                        Name:
                    </label>
                    <input type="text" class="form-control" name="name" value="<?php echo isset($userdata['name']) ? esc($userdata['name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="email">
                        Email Address:
                    </label>
                    <input type="text" class="form-control" name="email" value="<?php echo isset($userdata['email']) ? esc($userdata['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">
                        Contact Number
                    </label>
                    <input type="text" class="form-control" name="phone" value="<?php echo isset($userdata['phone']) ? esc($userdata['phone']) : ''; ?>">
                </div>
                <?php if (isset($update_error)): ?>
                    <div class="mt-3">
                        <div class="alert alert-danger" role="alert">
                            <strong>Update failed:</strong>
                            <ul class="mb-0">
                                <?php if (is_array($update_error)): foreach ($update_error as $err): ?>
                                    <li><?= esc(is_array($err) ? json_encode($err) : $err) ?></li>
                                <?php endforeach; else: ?>
                                    <li><?= esc($update_error) ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>