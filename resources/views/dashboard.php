<div class="row m-auto">
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <a class="text-decoration-none" href="/sales">
            <div class="card bg-primary bg-gradient text-white shadow">
                <div class="card-body">
                    <i class="fa fa-dollar display-2" aria-hidden="true"></i>
                    <span class="float-end fs-1 fw-bolder pe-3 count"><?= $data->sales_count ?></span>
                    <div class="text-end fw-semibold pe-2">Sales</div>
                </div>
                <div class="card-footer">
                    <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                    View Details
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <a class="text-decoration-none" href="/transactions">
            <div class="card bg-success bg-gradient text-white shadow">
                <div class="card-body">
                    <i class="fa fa-exchange display-2" aria-hidden="true"></i>
                    <span class="float-end fs-1 fw-bolder pe-3 count"><?= $data->transactions_count ?></span>
                    <div class="text-end fw-semibold pe-1">Transactions</div>
                </div>
                <div class="card-footer">
                    <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                    View Details
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <a class="text-decoration-none" href="/items">
            <div class="card bg-info bg-gradient text-white shadow">
                <div class="card-body">
                    <i class="fa fa-shopping-cart display-2" aria-hidden="true"></i>
                    <span class="float-end fs-1 fw-bolder pe-3 count"><?= $data->items_count ?></span>
                    <div class="text-end fw-semibold pe-2">Items</div>
                </div>
                <div class="card-footer">
                    <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                    View Details
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <a class="text-decoration-none" href="/users">
            <div class="card bg-danger bg-gradient text-white shadow">
                <div class="card-body">
                    <i class="fa fa-users display-2" aria-hidden="true"></i>
                    <span class="float-end fs-1 fw-bolder pe-3 count"><?= $data->users_count ?></span>
                    <div class="text-end fw-semibold pe-2">Users</div>
                </div>
                <div class="card-footer">
                    <i class="fa fa-arrow-right float-end mt-1" aria-hidden="true"></i>
                    View Details
                </div>
            </div>
        </a>
    </div>
</div>
<div class="container-fluid">
    <div class="row mt-5 d-flex flex-row">
        <!-- Items available Quantity -->
        <div class="col-12 col-lg-7 col-xl-8 h-100">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Item Stock</h6>
                </div>
                <div class="card-body">
                    <canvas id="stocksChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Table items -->
        <div class="col-12 col-lg-5 col-xl-4 mt-4 mt-lg-0 h-100">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Out of Stock Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-danger table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Available Stock</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-stocks">

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- TOP five expensive items -->
    <div class="row mt-5">
        <div class="col-12 col-lg-6 ">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Expensive Items</h6>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let itemsprices = [];
    let itemslabels = [];
    // to get items Top prices using the api
    $.ajax({
        type: "GET",
        url: "http://htu-pos.local/api/sales/topprices",
        success: function(response) {
            response.body.forEach(element => {
                itemslabels.push(element.name);
                itemsprices.push(element.price);


            }); //charts.js
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: itemslabels,
                    datasets: [{
                        label: 'Item Price',
                        data: itemsprices,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(153, 102, 255)',
                            'rgb(54, 162, 235)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(e) {
            console.log(e)
        }
    });
    // to get items available_quantity using the api
    let itemsStockName = [];
    let itemsStockValue = [];
    $.ajax({
        type: "GET",
        url: "http://htu-pos.local/api/sales",
        success: function(response) {
            response.body.forEach(element => {
                itemsStockName.push(element.name);
                itemsStockValue.push(element.available_quantity);
                //put not available stocks to tbl 
                if (element.available_quantity == 0) {
                    $('#tbody-stocks').append(`
                <tr>
                <td>${element.name}</td>
                <td>${element.available_quantity}</td>
                </tr>
                `);
                }
            }); //charts.js
            const ctx = document.getElementById('stocksChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: itemsStockName,
                    datasets: [{
                        label: 'Item Available Quantity',
                        data: itemsStockValue,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(54, 162, 235, 0.8)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(153, 102, 255)',
                            'rgb(54, 162, 235)'
                        ],
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    indexAxis: 'y'
                }
            });
        },
        error: function(e) {
            console.log(e)
        }
    });
</script>