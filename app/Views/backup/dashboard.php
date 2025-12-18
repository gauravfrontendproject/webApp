<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Simple Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 220px;
        }

        body {
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #0d6efd;
            color: #fff;
            position: fixed;
            padding: 1rem;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            display: block;
            padding: .35rem .25rem;
            border-radius: .35rem;
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.08);
        }

        .content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .content {
                margin-left: 0;
            }
        }

        .card-summary {
            min-width: 140px;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <aside class="sidebar d-none d-lg-block">
        <div class="mb-4">
            <div class="fs-4 fw-bold">YourApp</div>
            <small class="text-white-50">Dashboard</small>
        </div>

        <nav class="mb-4">
            <a href="#" class="active">Overview</a>
            <a href="#">Projects</a>
            <a href="#">Analytics</a>
            <a href="#">Settings</a>
        </nav>

        <div class="mt-auto">
            <small class="text-white-50">Signed in as</small>
            <div class="fw-semibold"><?= esc(session()->get('user_name') ?? 'User') ?></div>
            <a href="<?= site_url('logout') ?>" class="small text-white-50 d-block mt-2">Logout</a>
        </div>
    </aside>

    <!-- Mobile navbar (no logout) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">YourApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMobile"
                aria-controls="navMobile" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMobile">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Overview</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page content -->
    <main class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Dashboard</h3>
                <small class="text-muted">Welcome back, <?= esc(session()->get('user_name') ?? 'User') ?></small>
            </div>

            <div class="googlesheet mb-2">
                <a class="btn btn-warning" href="<?php echo base_url() ?>/exportuserdata">Export to googlesheet</a>
            </div>

            <!-- Summary cards -->
            <!-- <div class="d-flex gap-3 flex-wrap mb-4">
                <div class="card card-summary border-0 shadow-sm p-3">
                    <div class="small text-muted">Users</div>
                    <div class="h5 fw-bold">4,120</div>
                    <div class="small text-success">+5.2%</div>
                </div>

                <div class="card card-summary border-0 shadow-sm p-3">
                    <div class="small text-muted">Revenue</div>
                    <div class="h5 fw-bold">₹92,400</div>
                    <div class="small text-danger">-1.1%</div>
                </div>

                <div class="card card-summary border-0 shadow-sm p-3">
                    <div class="small text-muted">Active Projects</div>
                    <div class="h5 fw-bold">8</div>
                    <div class="small text-success">+2</div>
                </div>
            </div> -->

            <!-- Charts row -->
            <!-- <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Weekly Active Users</h6>
                                <small class="text-muted">Last 7 days</small>
                            </div>
                            <canvas id="lineChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Users by Device</h6>
                            <canvas id="pieChart" height="180"></canvas>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Recent Signups</h6>
                        <a href="#" class="small">View all</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Number</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($usersdata as $key => $val) { ?>
                                        <td><?php echo $val['name'] ?></td>
                                        <td><?php echo $val['phone'] ?></td>
                                        <td><?php echo $val['email'] ?></td>
                                        <td><a href="/editUser/<?php echo $val['id'] ?>"
                                                class="btn btn-sm btn-primary">Edit</a> | <a
                                                onclick=" return confirm('Are You Sure to delete this record.')"
                                                href="/deleteUser/<?php echo $val['id'] ?>"
                                                class="btn btn-sm btn-danger">Delete</a>
                                            | <a href="/upload/<?php echo $val['id'] ?>"
                                                class="btn btn-sm btn-danger">Upload Image</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>