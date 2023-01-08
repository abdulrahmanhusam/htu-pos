<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Tests;
use Core\Model\Item;
use Core\Model\Post;
use Core\Model\Transaction;
use Core\Model\users_transaction;
use Exception;

class Endpoints extends Controller
{
    use Tests;

    protected $request_body;
    protected $http_code = 200;

    protected $response_schema = array(
        "success" => true, // to provide the response status.
        "message_code" => "", // to provide message code for the front-end developer for a better error handling
        "body" => []
    );

    public function __construct()
    {
        $this->request_body = (array) json_decode(file_get_contents("php://input")); // to fill the request body if exists must casting to array
    }

    public function render()
    {
        header("Content-Type: application/json"); // changes the header information
        http_response_code($this->http_code); // set the HTTP Code for the response
        echo json_encode($this->response_schema); // convert the data to json format
    }

    public function items()
    {
        try {
            $item = new Item();
            $items = $item->get_all();
            if (empty($items)) {
                throw new \Exception('No items were found!');
            }
            $this->response_schema['body'] = $items;
            $this->response_schema['message_code'] = "Stocks_collected_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }

    public function transaction_create() //will insert to relation table also
    {
        self::check_if_field_empty($this->request_body, "/sales"); // for backend validation !empty


        try {
            $current_user_id = $this->request_body["user_id"]; //get the user id from request since the request_body casetd to array
            unset($this->request_body["user_id"]); //makes unset to prepare request_body for insertion to DB

            $transaction = new Transaction();
            $transaction->create($this->request_body); //transaction created

            //get the transaction id of the last record or row in the table transactions
            $transaction_id = $transaction->get_last_record_id(); //will return an assoccitve array of key id and value
            $transaction_id = (int) $transaction_id['id']; //get the value of the id element and cast in to int to match the field in DB
            //insert to the relation table (users_transactions) the user_id and transaction_id
            //Created a new model for code reuseability -DRY-
            $users_transactions = new users_transaction();
            $data = array("transaction_id" => $transaction_id, "user_id" => $current_user_id); //put it in array because create method recives just array param
            $users_transactions->create($data);

            //Proccess Updating available quantity in items table
            $item_data = array(); // store the returned item row
            $item_id = (int) $this->request_body['item_id']; //select item id to get data
            $selected_quantity = $this->request_body['quantity']; //select the required quantity
            $item = new Item();
            $item_data = $item->get_by_id($item_id);
            if (empty($item_data)) {
                throw new Exception("No item were found ");
            }
            $item_available_quantity = $item_data->available_quantity;
            $update_quantity_value = $item_available_quantity - $selected_quantity;
            $data_to_update = array("available_quantity" => $update_quantity_value, "id" => $item_id);
            $item->update_available_quantity($data_to_update);

            //proccess updating the item_name column in the transactions tbl
            $item_name = $item_data->name; //get the item name to update transactions tbl
            $update_name_data = array("item_name" => $item_name, "id" => $transaction_id);
            $transaction->update_itemname($update_name_data);

            //proccess adding the transaction to the bottom table
            $transaction_info = $transaction->get_by_id($transaction_id);
            if (empty($transaction_info)) {
                throw new Exception("No item were found ");
            }
            $this->response_schema['body'] = $transaction_info;

            $this->response_schema['message_code'] = "transaction_created_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 421;
        }
    }
    public function transaction_list() //by user id
    {
        //select tran_id from users_tran where user_id =id
        //store it in array then loop and
        //get the tran_id value and select * from tran where id =id and created=tody
        //or apply JOIN
        try {
            $current_user_id = (int) $this->request_body["user_id"];

            $transaction = new Transaction();
            $transactions = $transaction->get_today_user_transactions($current_user_id);
            if (empty($transactions)) {
                throw new \Exception('No transactions were found for this user!');
            }
            $this->response_schema['body'] = $transactions;
            $this->response_schema['message_code'] = "Transactions_BY_USER_ID_collected_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }
    /**
     * Transaction list by the transaction id to fill the edit form
     * @throws Exception
     * @return void
     */
    public function transaction_list_by_id() //by TRANSACTION id shows the transaction in the form to update
    {
        try {
            $received_transaction_id = (int) $this->request_body["transaction_id"];

            $transaction = new Transaction();
            $transactions_check_itemid = $transaction->get_by_id($received_transaction_id);
            if (empty($transactions_check_itemid->item_id)  || $transactions_check_itemid->item_id == "NULL") {
                throw new \Exception('The item is no longer available! -deleted item-');
            }
            $previous_item_name = $transactions_check_itemid->item_name; //getting the itemname by id
            $selected_transaction = $transaction->get_transaction_by_id($received_transaction_id);
            $selected_transaction->previous_item_name = $previous_item_name; //adding the previous name to the object results
            //process gitting the available quantity of this item to put it in max attr
            $item = new Item();
            $item_data = $item->get_by_id($transactions_check_itemid->item_id);
            $selected_transaction->item_available_quantity = $item_data->available_quantity;

            $this->response_schema['body'] = $selected_transaction;
            $this->response_schema['message_code'] = "Transactions_collected_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }

    public function transaction_update()
    {
        // tran id -> get the data for this tran from db ->send it with response
        try {
            //Proccess Updating available quantity in items table
            $item_data = array(); // store the returned item row (to get item info from items tbl)
            $item_id = (int) $this->request_body['item_id']; //select item id to get data
            $selected_quantity = $this->request_body['quantity']; //select the required quantity
            $previous_quantity = $this->request_body['previous_quantity']; //get the previous quantity
            $item = new Item();
            $item_data = $item->get_by_id($item_id);
            if (empty($item_data)) {
                throw new Exception("No item were found ");
            }
            $item_available_quantity = $item_data->available_quantity;
            $update_quantity_value = $item_available_quantity - ($selected_quantity - $previous_quantity);

            $data_to_update = array("available_quantity" => $update_quantity_value, "id" => $item_id);
            $item->update_available_quantity($data_to_update);

            //updating this transaction
            unset($this->request_body['previous_quantity']);
            $transaction_id = (int) $this->request_body['id'];
            $transaction = new Transaction();
            $transaction->update_transaction($this->request_body);

            //proccess adding the transaction to the bottom table
            $transaction_info = $transaction->get_by_id($transaction_id);
            if (empty($transaction_info)) {
                throw new Exception("No item were found ");
            }
            $this->response_schema['body'] = $transaction_info;


            $this->response_schema['message_code'] = "Transaction_Updated_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }
    /**
     * Delete selectd user transaction
     * @return void
     */
    public function transaction_delete()
    {
        // tran id -> get the data for this tran from db ->send it with response
        try {
            if (!isset($this->request_body['id'])) {
                throw new \Exception('id param not found');
            }
            $id = (int) $this->request_body['id'];

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

            $this->response_schema['message_code'] = "Transaction_Deleted_successfuly";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }

    /**
     * get top five expensive items (FOR USING in Admin dashboard chart)
     */
    public function items_top_prices()
    {
        try {
            $item = new Item();
            $items = $item->get_top_prices();
            if (empty($items)) {
                throw new \Exception('No items were found!');
            }
            $this->response_schema['body'] = $items;
            $this->response_schema['message_code'] = "items_TOP_price_collected";
        } catch (\Exception $error) {
            $this->response_schema['success'] = false;
            $this->response_schema['message_code'] = $error->getMessage();
            $this->http_code = 404;
        }
    }
}
