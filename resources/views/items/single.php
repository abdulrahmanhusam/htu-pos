<div class="container-fluid">
    <div class="row d-flex flex-row justify-content-center my-2">
        <div class="d-flex flex-row gap-3">
            <a href="/items" class="btn btn-outline-primary border border-5 border-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            <a href="/items/create" class="btn btn-outline-success border border-5 border-success"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
        </div>
        <div class="mt-4 mb-3">
            <h1 class="alert bg-light text-dark rounded-5 text-center shadow">
                <?= $data->item->name ?>
            </h1>
        </div>
        <div class="d-flex flex-row-reverse gap-3">
            <a href="/items/edit?id=<?= $data->item->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> <span class="d-none d-lg-inline-block">Edit</span></a>
            <a href="/items/delete?id=<?= $data->item->id ?>" class="btn btn-danger"><i class="fa fa-times"></i> <span class="d-none d-lg-inline-block">Delete</span></a>
        </div>
    </div>
    <div class="container text-center">
        <div class="row mt-5 justify-content-center align-items-center">
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Cost</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $data->item->cost ?> JOD
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-success">Price</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $data->item->price ?> JOD
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-danger">Available Quantity</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $data->item->available_quantity ?>
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5 justify-content-center align-items-center">
            <div class="col-md-6 col-lg-4 mt-3 mt-lg-0 ">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-muted">Created at</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <?php $orgdate = $data->item->created_at;
                            $newdate = date("d-M-Y", strtotime($orgdate));
                            echo $newdate; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>