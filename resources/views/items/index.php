<div class="container-fluid">
  <h1 class="text-center text-success bg-gradient name-font">Stocks List</h1>
  <hr class="w-50 m-auto  mb-5">
  <div class="row justify-content-between">
    <div class="col-12 col-md-6 col-lg-3 ">
      <div class="alert bg-danger text-white rounded text-center shadow">Items are Out of Stock </div>
      <div class="alert bg-warning  text-dark rounded text-center shadow">Items running out</div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 ">
      <div class="alert bg-light text-danger fw-bolder rounded-5 text-center shadow">Total Items: <?= $data->items_count ?></div>
      <a href="/items/create" class="text-white text-decoration-none">
        <div class="alert bg-success bg-gradient fw-bold rounded-5 text-center shadow-lg"><i class="fa fa-cart-plus" aria-hidden="true"></i>
          Add New Item</div>
      </a>
    </div>
    <hr>
  </div>
  <!-- </div><a href="items/create" class="btn btn-success float-end">Create Item</a> -->

  <div class="row my-5">
    <?php foreach ($data->items as $item) : ?>
      <div class="col-12 col-md-6 col-lg-4 col-xxl-2 mb-4">
        <a href="/item?id=<?= $item->id ?>" class="text-decoration-none">
          <div class="card shadow h-100 <?= ($item->available_quantity) == 0 ? "bg-danger text-white fw-bolder" : " " ?>  <?= ($item->available_quantity) <= 5 ? "bg-warning text-dark fw-bolder" : " " ?>">
            <div class="card-body text-center mb-2 mt-2">
              <h5 class="card-title"><?= $item->name ?></h5>
            </div>
            <div class="card-footer">
              <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
              View Details
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>

  </div>
</div>