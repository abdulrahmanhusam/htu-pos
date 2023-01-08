<h1 class="text-center text-info bg-gradient fw-bolder name-font">Update Item</h1>
<hr class="w-50 m-auto  mb-5">

<div class="container-fluid">
    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>
    <div class="row align-items-center">
        <form action="/items/update" method="POST">
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <input type="hidden" name="id" value="<?= $data->item->id ?>">
                <label for="item-name" class="form-label text-info">Item Name</label>
                <input type="text" class="form-control shadow" id="item-name" name="name" value="<?= $data->item->name ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-cost" class="form-label text-info">Item Cost</label>
                <input type="number" step=any min="0" class="form-control shadow" id="item-cost" name="cost" value="<?= $data->item->cost ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-price" class="form-label text-info">Item Selling Price</label>
                <input type="number" step=any class="form-control shadow" id="item-price" min="0" name="price" value="<?= $data->item->price ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-available_quantity" class="form-label text-info">Item Available Quantity</label>
                <input type="number" class="form-control shadow" id="item-available_quantity" min="0" name="available_quantity" value="<?= $data->item->available_quantity ?>" required>
            </div>

            <!-- step=any for to accept deciaml input or float -->
            <button type="submit" class="btn btn-warning mt-4"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> Update</button>
            <a href="/item?id=<?= $data->item->id ?>" class="btn btn-outline-danger ms-3 mt-4"><i class="fa fa-times"></i> Cancel</a>
        </form>
    </div>
</div>