<?php

use Core\Helpers\Helper;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <link rel="icon" type="image/x-icon" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/css/styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>


</head>

<body class="admin-view">

    <nav class="navbar navbar-dark bg-dark bg-gradient navbar-expand-lg">
        <div class="container-fluid">

            <a class="navbar-brand ms-1" href="/dashboard"><img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/images/brand.png" alt="brand"><span class="brand-font">HTU-POINT OF SALE</span> </a>
            <div class="d-flex flex-row-reverse">
                <li class="nav-item navbar-nav me-auto mb-2 mt-1 mb-lg-0 pe-5 d-none d-lg-block">
                    <a class="nav-link text-white border border-light border-1 rounded-5 name-font m-auto" href="/logout">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        <span class="pe-2 f5-4">Logout</span>
                    </a>
                </li>
                <li class="nav-item dropdown no-arrow navbar-nav me-auto mb-2 mb-lg-0 pe-5">
                    <a class="nav-link dropdown text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (is_null($_SESSION['user']['user_img'])) : ?>
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        <?php else : ?>
                            <img alt="profile-image" class="rounded-circle" src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/uploads/<?= $_SESSION['user']['user_img'] ?>" style="width: 35px;" />
                        <?php endif; ?>
                        <span class="pe-2 f5-4 name-font">Welcome <?= ucfirst($_SESSION['user']['display_name']) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/profile"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile</a></li>
                        <li><a class="dropdown-item" href="/profile/edit"><i class="fa fa-image fa-sm fa-fw mr-2 text-gray-400"></i>
                                Update Photo</a></li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </ul>
                </li>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div id="admin-area" class="row">
            <div id="fade-panel" class="col-2 min-vh-100 bg-dark ">
                <ul class="list-group list-group-flush mt-4">
                    <?php if (Helper::check_permission(['item:read', 'user:read', 'transaction:read', 'sales:all'])) : ?>
                        <li class="list-group-item list-group-item-action bg-dark text-white  <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') ? "opacity-100 " : "opacity-50" ?>">
                            <a class="text-white text-decoration-none" href="/dashboard"><i class="fa fa-tachometer pe-2 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Dashboard</span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (Helper::check_permission(['user:read'])) : ?>
                        <li class="list-group-item list-group-item-action bg-dark <?= strpos($_SERVER['REQUEST_URI'], 'user') ? "opacity-100" : "opacity-50" ?>">

                            <a class="text-white text-decoration-none fa-position" href="/users"><i class="fas fa-users pe-2 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Users</span></a>
                        </li><?php endif; ?>


                    <?php if (Helper::check_permission(['item:read'])) : ?>
                        <li class="list-group-item list-group-item-action bg-dark text-white <?= strpos($_SERVER['REQUEST_URI'], 'item') ? "opacity-100" : "opacity-50" ?>">

                            <a class="text-white text-decoration-none fa-position" href="/items"><i class="fa fa-shopping-cart pe-2 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Products</span></a>
                        </li><?php endif; ?>
                    <?php if (Helper::check_permission(['sales:all'])) : ?>
                        <li class="list-group-item list-group-item-action bg-dark text-white <?= strpos($_SERVER['REQUEST_URI'], 'sales') ? "opacity-100" : "opacity-50" ?>">

                            <a class="text-white text-decoration-none " href="/sales"><i class="fas fa-shopping-bag pe-2 md-ic"></i> <span class="d-none d-lg-inline-block">Sales</span></a>
                        </li><?php endif; ?>

                    <?php if (Helper::check_permission(['transaction:read'])) : ?>
                        <li class="list-group-item list-group-item-action bg-dark text-white <?= strpos($_SERVER['REQUEST_URI'], 'transaction') ? "opacity-100" : "opacity-50" ?>">

                            <a class="text-white text-decoration-none " href="/transactions"><i class="fa fa-exchange pe-2 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Transactions</span></a>
                        </li><?php endif; ?>


                    <li class="list-group-item list-group-item-action bg-dark text-white <?= strpos($_SERVER['REQUEST_URI'], 'profile') ? "opacity-100" : "opacity-50" ?>">

                        <a class="text-white text-decoration-none " href="/profile"><i class="fas fa-portrait pe-2 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Profile</span></a>

                    <li class="list-group-item list-group-item-action bg-dark text-white <?= strpos($_SERVER['REQUEST_URI'], 'logout') ? "opacity-100" : "opacity-50" ?>">

                        <a class="text-white text-decoration-none " href="/logout"><i class="fa fa-sign-out pe-1 md-ic" aria-hidden="true"></i> <span class="d-none d-lg-inline-block">Logout</span></a>
                    </li>
                    <!--   < endif; -->
                </ul>
            </div>
            <script type="text/javascript">
                /*             $('.row .col-2 ul li').mouseover(function () { 
                $(this).addClass("opacity-75");
            }); */
                /*          $('.row .col-2 ul').click(function () { 
                             $("li").each(function(){
                             $(this).toggleClass("opacity-100");
                             });
                         }); */
            </script>
            <div class="col-10 mb-5">
                <div class="container my-5">