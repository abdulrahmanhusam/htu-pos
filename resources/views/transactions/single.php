<?php

use Core\Helpers\Helper; ?>
<div class="container-fluid">
    <div class="row d-flex flex-row justify-content-center my-2">
        <div class="d-flex flex-row gap-3">
            <a href="/transactions" class="btn btn-outline-primary border border-5 border-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        </div>
        <div class="mt-4 mb-3">
            <h1 class="alert bg-light text-dark text-center shadow">
                <?= $data->transaction->item_name ?>
            </h1>
        </div>
        <div class="d-flex flex-row-reverse gap-3">
            <?php if (!is_null($data->transaction->item_id)) : ?>
                <a href="/transactions/edit?id=<?= $data->transaction->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> <span class="d-none d-lg-inline-block">Edit</span></a>
            <?php endif; ?>
            <?php if (Helper::check_permission(['transaction:delete'])) : ?>
                <a href="/transactions/delete?id=<?= $data->transaction->id ?>" class="btn btn-danger"><i class="fa fa-times"></i> <span class="d-none d-lg-inline-block">Delete</span></a><?php endif; ?>
        </div>
    </div>

    <div class="container ">
        <?php if (is_null($data->transaction->item_id)) : ?>
            <div class="alert alert-danger m-auto text-center">
                Can't edit this transaction Because the item is no longer available
            </div>
        <?php endif; ?>
        <div class="row mt-5 justify-content-center align-items-center text-center">
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Quantity</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $data->transaction->quantity ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-success">Total</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $data->transaction->total ?> JOD
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-muted">Created at</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?php $orgdate = $data->transaction->created_at;
                            $newdate = date("d-M-Y", strtotime($orgdate));
                            echo $newdate; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>