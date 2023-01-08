<?php

use Core\Helpers\Helper; ?>

<div class="row mt-5 d-flex flex-row justify-content-center">
    <div class="col-12 d-flex flex-row justify-content-center">
        <?php if (is_null($data->user->image)) : ?>
            <i class="fa fa-user-circle icon-font-size" aria-hidden="true"></i>
        <?php else : ?>
            <img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/uploads/<?= $data->user->image ?>" alt="User-img" class="w-25 rounded-circle img-mh">
        <?php endif; ?>
    </div>
    <div class="my-2">
        <h1 class="text-center">
            <?= $data->user->display_name ?>
        </h1>
    </div>

    <div class="d-flex flex-row-reverse gap-3">
        <a href="/profile/edit" class="btn btn-warning"><i class="fas fa-user-edit"></i> <span class="d-none d-lg-inline-block">Edit</span></a>
    </div>
</div>

<div class="container ">
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Email</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?= $data->user->email ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-success">Username</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?= $data->user->username ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-danger">Role</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?= Helper::get_role($data->user) ?>
                    </p>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-info">Display Name</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?= $data->user->display_name ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-muted">Creation Date</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?php $orgdate = $data->user->created_at;
                        $newdate = date("d-M-Y", strtotime($orgdate));
                        echo $newdate; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>