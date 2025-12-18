<?php
$session = session();
// Ensure we have a simple boolean to check login state without risking undefined index notices
$isLoggedIn = (bool) $session->get('isLoggedIn');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Page design</title>
    <!--bootstrap links-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&display=swap" rel="stylesheet">
    <!--css link-->
    <link rel="stylesheet" href="css/style.css">
    <!--owl slider link-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

</head>

<body>
    <!--bottom-header section start hare-->
    <header class="bottom_headersc">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light ">
                <a class="navbar-brand" href="#"> <img src="images/logo-5.png" class="logo"> </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Top Offers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orderlist.html">Orderlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Fashion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Electronics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Toys & More</a>
                        </li>
                    </ul>
                    <div class="bottom_hiconbox">
                        <a href="login.html"></a>
                        <svg xmlns="http://www.w3.org/2000/svg" class="bottom_hicon1" width="16" height="16"
                            fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                            <path
                                d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                        </svg>
                        <!-- <span class="badge badge-primary">1</span> -->
                        <?php if ($isLoggedIn) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="bottom_hicon" fill="currentColor"
                                        class="bi bi-person" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    </svg>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="javascript:void(0)">Profile</a>
                                    <a class="dropdown-item" href="<?php echo base_url('logout'); ?>">Log out</a>
                                </div>

                            </li>
                        <?php } else { ?>
                            <a href="<?= site_url('login') ?>">Login</a>
                        <?php } ?>
                    </div>

                </div>
            </nav>
        </div>
    </header>