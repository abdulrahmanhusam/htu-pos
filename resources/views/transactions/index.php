    <div class="container">
        <h1 class="text-center text-success bg-gradient name-font">Transactions List</h1>
        <hr class="w-50 m-auto  mb-5">

        <div class="row justify-content-between">
            <div class="col-12 col-md-6 col-lg-6 ">
                <div class="alert bg-warning bg-gradient text-dark rounded text-center shadow">Large Transactions <span class="text-muted">(Total more than 1000)</span></div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 ">
                <div class="alert bg-light text-danger fw-bolder rounded-5 text-center shadow">Total Transactions: <?= $data->transactions_count ?></div>
            </div>
            <hr>
        </div>

        <div class="row my-5 p-0">
            <?php $counter = 1 ?>
            <div class="col p-0">
                <div id="dataTableContainer">
                    <table class="table table-responsive table-info shadow text-center">
                        <thead class=" table-dark">
                            <tr>
                                <th class="d-none d-md-table-cell" scope="col">#</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>

                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php foreach ($data->transactions as $transaction) : ?>
                                <tr>
                                    <th class="d-none d-md-table-cell <?= ($transaction->total) >= 1000 ? "bg-warning" : " " ?>" scope="row"><?= $counter++ ?></th>
                                    <td class="<?= ($transaction->total) >= 1000 ? "bg-warning" : " " ?>"><?= $transaction->item_name ?></td>
                                    <td class="<?= ($transaction->total) >= 1000 ? "bg-warning" : " " ?>"><?= $transaction->quantity ?></td>
                                    <td class="<?= ($transaction->total) >= 1000 ? "bg-warning" : " " ?>"><?= $transaction->total ?></td>
                                    <td class="<?= ($transaction->total) >= 1000 ? "bg-warning" : " " ?>"><a href="/transaction?id=<?= $transaction->id ?>" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> <span class="d-none d-lg-inline-block">Show Details</span></a></td>
                                    <!-- to pass multiple variables in href use & between each var (rather than creating a new function DRY)-->
                                </tr>
                        </tbody>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>


        </div>
    </div>