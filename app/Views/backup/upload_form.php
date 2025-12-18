<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login — Demo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($validation)): ?>
        <div class="col-12">
            <div class="alert alert-danger" role="alert" style="color:red;">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <div class="container mt-2">
            <div class="col-md-6 d-flex justify-content-center flex-column">
                <h4>Upload Image</h4>
                <?php if (session()->getFlashdata('Flash_message')): ?>
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            <?= esc(session()->getFlashdata('Flash_message')) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($upload_error)): ?>
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <?= esc($upload_error) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="file" name="userfile" size="20" />
                    </div>
                    <button type="submit" class="btn btn-info">Upload</button>
                </form>
            </div>
        </div>
</body>

</html>