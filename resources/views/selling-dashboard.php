<body onload="loadjspage();" class="container my-5">
    <h1 class="text-center text-success bg-gradient name-font">Selling Dashboard</h1>
    <hr class="w-50 m-auto  mb-5">
    <div class="card mb-5 pt-3 p-0 p-lg-4 shadow rounded">
        <form id="userInputContainer" class="m-auto d-sm-block d-lg-flex justify-content-between w-100">

            <input type="hidden" value="<?= $_SESSION['user']['user_id'] ?>">
            <div class="input-group flex-nowrap pe-3 ps-2 mb-2 mb-md-3 mb-lg-0">
                <span class="input-group-text" id="addon-wrapping">Items</span>
                <select name="items-select" id="items-select" class="form-select" aria-label="Default select example">
                    <option value="">Choose an Item</option>
                </select>
            </div>

            <div class="input-group flex-nowrap pe-3 ps-2 mb-2 mb-md-3 mb-lg-0">
                <span class="input-group-text" id="addon-wrapping">Quantity</span>
                <input id="quantity" type="number" class="form-control" aria-describedby="addon-wrapping" min="1" required>
            </div>


            <div class="input-group flex-nowrap pe-3 ps-2 mb-2 mb-md-3 mb-lg-0">
                <span class="input-group-text" id="addon-wrapping">Price (JOD)</span>
                <input id="price" type="number" step="any" class="form-control" aria-describedby="addon-wrapping" readonly>
            </div>

            <button id="buy-item" class="btn btn-success ms-2 ms-lg-0">Sell</button>
            <button id="update-item" class="btn btn-warning me-2">Update</button>
            <button id="cancel" class="btn btn-primary">Cancel</button>

        </form>
        <div id="msg" class="mt-2"></div><!-- success message -->
        <p id="max-q" class="text-muted text-center"></p><!-- max quantity max txt -->
        <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
            <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
        <?php $_SESSION['backend_validation_error'] = null; ?>
    </div>
    <h2 class="text-center text-success bg-gradient name-font">Your Today Transactions</h2>
    <hr class="w-50 m-auto ">
    <img id="confetti" src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/images/6ob.gif" class=" min-vh-100 w-50 position-absolute top-50 start-50 translate-middle">
    <div id="dataTableContainer" class="mt-2">
        <table id="table" class="table table-hover table-responsive table-bordered  table-primary shadow text-center ">
            <thead class=" table-dark">
                <tr>

                    <th scope="col">Item</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price Per Unit</th>
                    <th scope="col">Total</th>
                    <th class="text-center" scope="col">Action</th>

                </tr>
            </thead>
            <tbody id="tbody">


            </tbody>
        </table>


    </div>

    <!-- WE CANT USE READY DOCUMENT FUNCTION IN PAGE JS CODE SO WE MADE FUNCTION AND GIVE THE BOFY ONLOAD= ATTR -->
    <script type="text/javascript">
        let itemID = null; // global on all functions (class) because i need to use it in diff scopes unside the file
        let itemPrice = null; // I need it to be always updated not re initilized 

        //for vaildate the max front and back
        let maxAllowedQuantity = null;
        //for vaildate the max (UPDATE) Qantity front and back
        let maxAllowedUpdateQuantity = null;
        let host = window.location.origin; //to get the http protocol and domain name 

        function loadjspage() {

            $('#update-item').hide("fast", "linear"); // hide the update btn
            $('#cancel').hide("fast", "linear"); //hide cancel button
            $('#confetti').hide("fast", "linear"); //hide confetti

            let itemQuantity = null;
            let currentUserId = <?= $_SESSION['user']['user_id'] ?>;
            //get items from items table and append it
            $.ajax({
                type: "GET",
                url: host + "/api/sales",
                success: function(response) {
                    response.body.forEach(element => {
                        if (selectedItemNameToupdate == element.name) { // this step to update out of stock transaction item
                            if (element.available_quantity == "0") { //will check if the item out of stock
                                return true; //like continue in php to skip one iteration
                            }
                        }
                        $('#items-select').append(`
                <option value="${element.name}">
                     ${element.name}
                </option>
                `);

                        //let price= parseFloat(element.price);
                        // document.getElementById("quantity").defaultValue = 1;
                        //$("#price").val(element.price[0]);

                    });
                },
                error: function(e) {
                    console.log(e)
                }
            });
            //when change the items
            $('#items-select').change(function() {
                $('#max-q').empty(); //empty max quantity p
                //Use $option (with the "$") to see that the variable is a jQuery object
                let option = $(this).find('option:selected');
                let value = option.val();
                //Added with the EDIT
                $.ajax({
                    type: "GET",
                    url: host + "/api/sales",
                    success: function(response) {
                        response.body.forEach(element => {
                            if (element.name == value) {
                                itemID = element.id; //get the selectd item id to make the transaction
                                $('#quantity').attr({
                                    "max": element.available_quantity
                                });
                                maxAllowedQuantity = element.available_quantity;
                                if (maxAllowedQuantity == 0) {
                                    $('#max-q').append(`
                                    <div class="alert alert-danger w-50 m-auto"  role="alert">
                                    Item Out of Stock
                                    </div>
                                  `);
                                } else {
                                    //Max quantity MSG
                                    $('#max-q').append(`
                                    max = ${maxAllowedQuantity}
                                  `);
                                }
                                $("#quantity").val(1); //on item change set quantitiy to 1 |was document.getElementById("quantity").defaultValue = 1;
                                $("#price").val(element.price);
                                itemPrice = parseFloat($("#price").val()); //type number also to make calulations in change quantity method
                            }

                        });
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            });

            $('#quantity').change(function(e) {
                e.preventDefault();
                itemQuantity = parseFloat($('#quantity').val());
                let totalItemPrice = (itemQuantity * itemPrice).toFixed(2); //round the float value to 2 deciaml digits
                $("#price").val(totalItemPrice); //since the price datatype in DB is float of len(10,2) will made auto rounding 2 digits




            });



            $('#buy-item').click(function(e) {
                e.preventDefault();
                let quantity = $('#quantity').val();
                let price = $("#price").val()
                let itemsSelector = $('#items-select').val();
                if (quantity == "" || price == "" || itemsSelector == "") {
                    alert("You have to fill some data before purchase");
                    return;
                }
                if (quantity > maxAllowedQuantity) {
                    alert("Exceeded the available quantity Which is: " + maxAllowedQuantity);
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: host + "/api/sales/create",
                    data: JSON.stringify({
                        item_id: itemID,
                        quantity: quantity,
                        total: price,
                        user_id: currentUserId // this is an additional field not in the transactions table 

                    }),
                    success: function(response) {
                        //confetti show
                        $("#confetti").show();
                        setTimeout(() => {
                            {
                                $("#confetti").hide();
                            }
                        }, 2000);
                        //success MSG
                        $('#msg').append(`
                        <p class="alert alert-success w-50 m-auto"  role="alert">
                        Item bought successfully
                        </p>
                        `);
                        setTimeout(() => {
                            {
                                $("#msg").remove();
                            }
                        }, 5000);
                        //set the data of this transaction in the bottom table
                        let pricePerUnit = (response.body.total / response.body.quantity).toFixed(2); //rather than getting price from items
                        $('#tbody').append(`
                          <tr>
                            <td>${response.body.item_name}</td>
                            <td>${response.body.quantity}</td>
                            <td>${pricePerUnit}</td>
                            <td>${response.body.total}</td>
                            <td><a id="editTransacion" onClick="editTransacion(${response.body.id})" class="btn btn-warning"><i class="fas fa-edit"></i> <span class="d-none d-lg-inline-block">Edit</span></a> <a id="deleteTransacion" onClick="deleteTransacion(${response.body.id})" class="btn btn-danger"><i class="fa fa-times"></i> <span class="d-none d-lg-inline-block">Delete</span></a></td>
                            </tr>
                        `);
                        //empty fields 
                        $('#quantity').val("");
                        $("#price").val("");
                        $('#items-select').val("");
                        $('#max-q').empty();

                    },
                    error: function(e) {
                        console.log(e)
                    }
                });

            });

            //to get the transactions of this logged in user 
            $.ajax({
                type: "POST",
                url: host + "/api/sales/list",
                data: JSON.stringify({
                    user_id: currentUserId // this is an additional field not in the transactions table 
                }),
                success: function(response) {
                    console.log(response);
                    response.body.forEach(element => {
                        let pricePerUnit = (element.total / element.quantity).toFixed(2); //rather than getting price from items
                        $('#tbody').append(`
                          <tr>
                            <td>${element.item_name}</td>
                            <td>${element.quantity}</td>
                            <td>${pricePerUnit}</td>
                            <td>${element.total}</td>
                            <td><a id="editTransacion" onClick="editTransacion(${element.id})" class="btn btn-warning"><i class="fas fa-edit"></i> <span class="d-none d-lg-inline-block">Edit</span></a> <a id="deleteTransacion" onClick="deleteTransacion(${element.id})" class="btn btn-danger"><i class="fa fa-times"></i> <span class="d-none d-lg-inline-block">Delete</span></a></td>
                            </tr>
                        `);

                    });

                },
                error: function(e) {
                    console.log(e)
                }
            });


        } //end of on load function

        let selectedItemNameToupdate = null;
        let selectedItemQuantityToupdate = null;
        let checkPrevious_item_name = null;

        function editTransacion(id) {
            $.ajax({
                type: "POST",
                url: host + "/api/sales/list/single",
                data: JSON.stringify({
                    transaction_id: id

                }),
                success: function(response) { // !Since the result is just one row we dont need forEach loop
                    $('#max-q').empty(); //empty max quantity paragraph
                    $('#buy-item').hide("slow", "linear");
                    $('#update-item').show();
                    $('#cancel').show();
                    //get the item name and quantity to check if user make change or not
                    selectedItemNameToupdate = response.body.name;
                    selectedItemQuantityToupdate = response.body.quantity;
                    //check if the item name updated from stock
                    checkPrevious_item_name = response.body.previous_item_name;
                    if (checkPrevious_item_name != selectedItemNameToupdate) {
                        $('#msg').append(`
                        <p class="alert alert-primary w-50 m-auto"  role="alert">
                        The item name updated from stock management!
                        </p>
                        `);
                        setTimeout(() => {
                            {
                                $("#msg").remove();
                            }
                        }, 5000);

                    }
                    //changing the max available_quantity of an element for ex u have 4 paid and to need + 
                    $('#quantity').attr({
                        "max": response.body.item_available_quantity + selectedItemQuantityToupdate
                    });
                    maxAllowedUpdateQuantity = response.body.item_available_quantity + selectedItemQuantityToupdate;

                    //Max quantity MSG
                    $('#max-q').append(`
                        max = ${maxAllowedUpdateQuantity}
                        `);

                    $('select[name="items-select"]').val(response.body.name); //set the item name
                    $('#quantity').val(response.body.quantity);
                    $("#price").val(response.body.total);
                    itemPrice = parseFloat(response.body.price); // to change it with quantity changes
                    itemID = (response.body.item_id);

                },
                error: function(e) {
                    console.log(e);
                    alert("Can't edit this transaction, the item is no longer available in the stock!");
                    return true;
                }
            });

            //handle cancel update process
            $('#cancel').click(function(e) {
                e.preventDefault();
                $('#max-q').empty(); //empty max quantity paragraph
                $('#quantity').val("");
                $("#price").val("");
                $('#items-select').val("");
                $('#update-item').hide("fast", "linear");
                $('#cancel').hide("fast", "linear");
                $('#buy-item').show("slow");
                let rIndex = null;
                let cIndex = null;

            });
            // the process of gitting selected row and it cells
            let table = document.getElementById("table");
            let rIndex = null;
            let cIndex = null;
            for (let i = 0; i < table.rows.length; i++) { //tbl rows
                for (let j = 0; j < table.rows[i].cells.length; j++) { // for row cells

                    table.rows[i].cells[j].onclick = function() {
                        if (rIndex == null) {
                            rIndex = this.parentElement.rowIndex;
                            cIndex = this.cellIndex;

                        }

                    };
                }

            }


            $('#update-item').click(function(e) {
                e.preventDefault();
                let quantity = $('#quantity').val();
                let price = $("#price").val();
                let itemsSelector = $('#items-select').val();


                if (quantity == "" || price == "" || itemsSelector == "") {
                    alert("You have to fill some data before purchase");
                    return;
                }
                //check if the user made changes on items or quantity or not without it will subtract another time from available quantity
                if (selectedItemNameToupdate == itemsSelector && selectedItemQuantityToupdate == quantity) {
                    alert("Please make some change to update");
                    return; //don't skip but stop the event
                }
                if (quantity > maxAllowedUpdateQuantity) {
                    alert("Exceeded the available quantity");
                    return;
                }


                $.ajax({
                    type: "PUT",
                    url: host + "/api/sales/update",
                    data: JSON.stringify({ // we can put these data in assoc array rather than this steps and DTcasting in api controller 
                        item_id: itemID, //public in on change quantity method
                        item_name: itemsSelector,
                        quantity: quantity,
                        previous_quantity: selectedItemQuantityToupdate,
                        total: price,
                        id: id //this is the transaction id to update

                    }),
                    success: function(response) {
                        //confetti show
                        $("#confetti").show();
                        setTimeout(() => {
                            {
                                $("#confetti").hide();
                            }
                        }, 2000);
                        //success MSG
                        $('#msg').append(`
                        <p class="alert alert-success w-50 m-auto"  role="alert">
                        Item Updated successfully
                        </p>
                        `);
                        setTimeout(() => {
                            {
                                $("#msg").remove();
                            }
                        }, 5000);
                        //update the data of this transaction in the bottom table
                        let unitprice = (response.body.total / response.body.quantity).toFixed(2);
                        table.rows[rIndex].cells[0].innerHTML = response.body.item_name;
                        table.rows[rIndex].cells[1].innerHTML = response.body.quantity;
                        table.rows[rIndex].cells[2].innerHTML = unitprice;
                        table.rows[rIndex].cells[3].innerHTML = response.body.total;


                        //empty fields
                        $('#quantity').val("");
                        $("#price").val("");
                        $('#items-select').val("");
                        $('#max-q').empty();
                        rIndex = null;
                        cIndex = null;
                        id = null;
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });


            });

        }

        function deleteTransacion(id) {
            // the process of gitting selected row 
            let table = document.getElementById("table"),
                rIndex; // defining tow var in one stmt ,
            for (let i = 0; i < table.rows.length; i++) {
                table.rows[i].onclick = function() {
                    rIndex = this.rowIndex;

                }
            }

            $.ajax({
                type: "DELETE",
                url: host + "/api/sales/delete",
                data: JSON.stringify({
                    id: id //this is the current transaction id to delete

                }),
                success: function(response) {
                    //confetti show
                    $("#confetti").show();
                    setTimeout(() => {
                        {
                            $("#confetti").hide();
                        }
                    }, 2000);
                    //hide the table row process 
                    table.rows[rIndex].remove();

                },
                error: function(e) {
                    console.log(e)
                }
            });


        }
    </script>