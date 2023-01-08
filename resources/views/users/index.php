<?php

use Core\Helpers\Helper;

?>
<div class="container-fluid">
    <h1 class="text-center text-success bg-gradient name-font">Users List</h1>
    <hr class="w-50 m-auto  mb-5">
    <div class="row ">
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="alert bg-primary bg-gradient text-white rounded-5 text-center shadow">Admin Users</div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="alert bg-success bg-gradient text-white rounded-5 text-center shadow">Procurement Managers</div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="alert bg-info text-white rounded-5 text-center shadow">Seller Users</div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="alert bg-dark bg-gradient text-white rounded-5 text-center shadow">Accountant Users</div>
        </div>
        <hr>
    </div>
    <div class="row d-flex flex-row-reverse justify-content-between mt-2">
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="alert bg-light text-danger fw-bolder rounded-5 text-center shadow">Total Users: <?= $data->users_count ?></div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <a href="/users/create" class="text-white text-decoration-none">
                <div class="alert bg-success fw-bold rounded-3 text-center shadow-lg"><i class="fa fa-user-plus" aria-hidden="true"></i>
                    Add User</div>
            </a>
        </div>

    </div>
    <hr class="w-75 m-auto">
    <div class="row m-auto mt-5">
        <?php foreach ($data->users as $user) :
            if (Helper::get_role($user) == "Admin") { ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <a class="text-decoration-none" href="/user?id=<?= $user->id ?>">
                        <div class="card bg-primary bg-gradient  text-white shadow h-100">
                            <div class="card-header "><?= Helper::get_role($user); ?></div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $user->display_name ?></h5>
                            </div>
                            <div class="card-footer">
                                <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                                Check User
                            </div>
                        </div>
                    </a>
                </div>
            <?php } elseif (Helper::get_role($user) == "Procurement manager") {
            ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <a class="text-decoration-none" href="/user?id=<?= $user->id ?>">
                        <div class="card bg-success bg-gradient  text-white shadow h-100">
                            <div class="card-header "><?= Helper::get_role($user); ?></div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $user->display_name ?></h5>
                            </div>
                            <div class="card-footer">
                                <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                                Check User
                            </div>
                        </div>
                    </a>
                </div>
            <?php } elseif (Helper::get_role($user) == "Accountant") {
            ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <a class="text-decoration-none" href="/user?id=<?= $user->id ?>">
                        <div class="card bg-dark bg-gradient  text-white shadow h-100">
                            <div class="card-header "><?= Helper::get_role($user); ?></div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $user->display_name ?></h5>
                            </div>
                            <div class="card-footer">
                                <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                                Check User
                            </div>
                        </div>
                    </a>
                </div>
            <?php } elseif (Helper::get_role($user) == "seller") {
            ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <a class="text-decoration-none" href="/user?id=<?= $user->id ?>">
                        <div class="card bg-info  text-white shadow h-100">
                            <div class="card-header "><?= Helper::get_role($user); ?></div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $user->display_name ?></h5>
                            </div>
                            <div class="card-footer">
                                <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                                Check User
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>

        <?php endforeach; ?>


    </div>
</div>