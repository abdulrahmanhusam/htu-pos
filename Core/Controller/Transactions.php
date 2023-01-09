<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Base\View;
use Core\Helpers\Helper;
use Core\Helpers\Tests;
use Core\Model\Item;
use Core\Model\Transaction;
use Core\Model\users_transaction;

class Transactions extends Controller
{
    use Tests;

    public function render()
    {
        if (!empty($this->view)) {
            $this->view();
        }
    }

    public function __construct()
    {
        $this->auth();
        $this->admin_view(true);
    }

    /**
     * Gets all transactions
     *
     * @return void
     */
    public function index()
    {
        $this->permissions(['transaction:read']);
        $this->view = 'transactions/index';
        $transaction = new Transaction(); // new model transaction.
        $this->data['transactions'] = $transaction->get_all();
        $this->data['transactions_count'] = count($transaction->get_all());
    }
    /**
     * get Seleted transaction
     *
     * @return void
     */
    public function single()
    {
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        $this->permissions(['transaction:read']);
        $this->view = 'transactions/single';
        $transaction = new Transaction();
        $this->data['transaction'] = $transaction->get_by_id($_GET['id']);
    }


    /**
     * Display the HTML form for transaction update
     *
     * @return void
     */
    public function edit()
    {
        $this->permissions(['transaction:read', 'transaction:update']);
        $this->view = 'transactions/edit';
        $transaction = new Transaction();
        $this->data['transaction'] =  $transaction->get_by_id($_GET['id']);
        //get the item available quantity
        $item_id = $this->data['transaction']->item_id;
        $item = new Item();
        $this->data['item'] =  $item->get_by_id($item_id);
    }

    /**
     * Update the transaction
     *
     * @return void
     */
    public function update()
    {
        $this->permissions(['transaction:read', 'transaction:update']);
        self::check_if_field_empty($_POST, "/transactions/edit?id=" . $_POST['id']); // form back-end validation
        //Proccess Updating available quantity in items table
        //the equation is available quantity= new quantity -previous quantity
        $item_data = array(); // store the returned item row (to get item info from items tbl)
        $item_id = (int) $_POST['item_id']; //select item id to get data
        $selected_quantity = (int) $_POST['quantity']; //select the required quantity (new quantity or required to update)
        $previous_quantity = (int) $_POST['previous_quantity']; //the quantity that user have paid(own) previously

        // we can get it from edit form input available-quantity bu security breach if changed
        $item = new Item();
        $item_data = $item->get_by_id($item_id);
        $item_available_quantity = $item_data->available_quantity;

        $update_quantity_value = $item_available_quantity - ($selected_quantity - $previous_quantity);
        $data_to_update = array("available_quantity" => $update_quantity_value, "id" => $item_id);
        $item->update_available_quantity($data_to_update);

        //updating quantity and total in transactions tbl process
        unset($_POST['previous_quantity']);
        unset($_POST['item_id']);
        $transaction = new Transaction();
        $transaction->update($_POST);
        Helper::redirect('/transaction?id=' . $_POST['id']);
    }

    /**
     * Delete the transaction
     *
     * @return void
     */
    public function delete()
    {
        $this->permissions(['transaction:read', 'transaction:delete']);
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        $id = (int) $_GET['id'];

        //update the avaliable quantity in items tbl if exists
        $transaction = new Transaction();
        $transactions = $transaction->get_by_id($id);
        $item_id = $transactions->item_id;
        if (!is_null($item_id) || !empty($item_id)) {
            $returned_quantity = $transactions->quantity;
            $item = new Item();
            $items = $item->get_by_id($item_id);
            $available_quantity = $items->available_quantity; //get the available quantity for this item to summation
            $updated_quantity = $available_quantity + $returned_quantity;
            $data = array("id" => $item_id, "available_quantity" => $updated_quantity);
            $item->update_available_quantity($data);
        }
        //deleting user transaction proccess.
        $users_transaction = new users_transaction(); //delete should be from relation table since this transaction related to user id (spcific user) FOREIGN KEY CONSTRAINT
        $users_transaction->delete_by_transaction_id($id);

        //deleting the transaction from transactions table
        $transaction->delete($id);

        Helper::redirect('/transactions');
    }
}
