<body onload="loadJscode();">
    <h1 class="text-center text-info bg-gradient fw-bolder name-font">Update Transaction</h1>
    <hr class="w-50 m-auto  mb-5">

    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50 m-auto" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>

    <form action="/transactions/update" method="POST" id="userInputContainer" class="m-auto my-5 w-50">

        <input type="hidden" name="id" value="<?= $data->transaction->id ?>">
        <input type="hidden" name="item_id" value="<?= $data->transaction->item_id ?>">
        <input type="hidden" name="previous_quantity" value="<?= $data->transaction->quantity ?>">
        <input type="hidden" id="available-quantity" value="<?= $data->item->available_quantity ?>"><!-- For max quantity equation -->

        <div class="input-group flex-nowrap my-3">
            <span class="input-group-text text-info" id="addon-wrapping">Items</span>
            <select id="items-select" class="form-select" aria-label="Default select example">
                <option value="<?= $data->transaction->item_name ?>"><?= $data->transaction->item_name ?></option>
            </select>

        </div>

        <div class="input-group flex-nowrap my-3">
            <span class="input-group-text text-info" id="addon-wrapping">Quantity</span>
            <input value="<?= $data->transaction->quantity ?>" max="<?= $data->item->available_quantity ?>" name="quantity" id="quantity" type="number" class="form-control" aria-describedby="addon-wrapping" min="1" required>
        </div>

        <div class="input-group flex-nowrap my-3">
            <span class="input-group-text text-info" id="addon-wrapping">Price (JOD)</span>
            <input value="<?= $data->transaction->total ?>" name="total" id="price" type="number" step="any" class="form-control" aria-describedby="addon-wrapping" readonly>
        </div>

        <button type="submit" id="update-transaction" class="btn btn-warning me-3 float-end"><i class="fas fa-edit" aria-hidden="true"></i> Update</button>
        <a href="/transaction?id=<?= $data->transaction->id ?>" class="btn btn-outline-danger ms-3"><i class="fa fa-times"></i> Cancel</a>

    </form>
</body>
<script type="text/javascript">
    let basicQuantity = parseInt($('#quantity').val()); //define it on class scope not function scope(the quantity that user has paid)
    let availableQuantityforIteminDB = parseInt($('#available-quantity').val());

    function loadJscode() {
        //changes the price with quantity
        let totalPrice = $('#price').val();

        let priceperunit = (totalPrice / basicQuantity);

        $('#quantity').change(function(e) {
            e.preventDefault();
            let itemQuantity = parseFloat($('#quantity').val());
            let totalItemPrice = (itemQuantity * priceperunit).toFixed(2); //round the float value to 2 deciaml digits
            $("#price").val(totalItemPrice); //since the price datatype in DB is float of len(10,2) will made auto rounding 2 digits

        });
        //changing the max available_quantity of an element for basicQuantity is the reserved user quantity + available_quantity in item to update
        //get the item available-quantity to add it to the quantity the user have in this transaction
        $('#quantity').attr({
            "max": availableQuantityforIteminDB + basicQuantity
        });

    }
    //will check if the quantity changed if yes make form action other wise prevent Submiiting
    $('#update-transaction').click(function(e) {

        let updatedQuantity = $('#quantity').val();
        if (updatedQuantity == basicQuantity) {
            alert("Please Make some Changes to Update");
            e.preventDefault(); //to prevent Form Submiiting.
            return;
        }
        if (updatedQuantity > availableQuantityforIteminDB + basicQuantity) {
            e.preventDefault(); //to prevent Form Submiiting.
            alert("Exceeded the available quantity");
            return;
        }

    });
    $('#cancel').click(function(e) {
        e.preventDefault();
        window.history.back();


    });
</script>