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
            <?php if (isset($flash_mesage)): ?>
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        Congratulations! Updated Successfully.
                    </div>
                </div>
            <?php endif; ?>
            <form action="<?php echo base_url() ?> editUser" method="post">
                <div class="form-group">
                    <label for="fullname">
                        Name:
                    </label>
                    <input type="text" class="form-control" name="name" value="<?php echo $userdata['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">
                        Email Address:
                    </label>
                    <input type="text" class="form-control" name="email" value="<?php echo $userdata['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">
                        Contact Number
                    </label>
                    <input type="text" class="form-control" name="phone" value="<?php echo $userdata['phone']; ?>">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>