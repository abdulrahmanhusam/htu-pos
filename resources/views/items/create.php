<h1 class="text-center text-info bg-gradient fw-bolder name-font">Create New Item</h1>
<hr class="w-50 m-auto  mb-5">

<div class="container-fluid">
    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>
    <div class="row align-items-center">
        <form action="/items/store" method="POST">
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0 ">
                <label for="item-name" class="form-label text-info">Item Name</label>
                <input type="text" class="form-control shadow" id="item-name" name="name" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-cost" class="form-label text-info">Item Cost</label>
                <input type="number" step=any min="0" class="form-control shadow" id="item-cost" name="cost" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-price" class="form-label text-info">Item Selling Price</label>
                <input type="number" step=any class="form-control shadow" id="item-price" min="0" name="price" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="item-available_quantity" class="form-label text-info">Item Available Quantity</label>
                <input type="number" class="form-control shadow" id="item-available_quantity" min="0" name="available_quantity" required>
            </div>

            <!-- step=any for to accept deciaml input or float -->
            <button type="submit" class="btn btn-success mt-4"><i class="fa fa-cart-plus" aria-hidden="true"></i> Create</button>
            <a href="/items" class="btn btn-outline-danger ms-3 mt-4"><i class="fa fa-times"></i> Cancel</a>
        </form>
    </div>
</div>