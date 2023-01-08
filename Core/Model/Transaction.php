<?php

namespace Core\Model;

use Core\Base\Model;

class Transaction extends Model
{
    public function get_last_record_id()
    {
        $stmt = $this->connection->prepare("SELECT (id) FROM $this->table ORDER BY id DESC LIMIT 1"); // prepare the sql statement
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        $stmt->close();
        // $result = $this->connection->query("SELECT * FROM $this->table WHERE id=$id");
        return $result->fetch_assoc();
    }

    public function get_today_user_transactions($id)
    {
        $data = array();
        $stmt = $this->connection->prepare("SELECT transactions.id,transactions.quantity,transactions.total,transactions.item_name FROM transactions INNER JOIN users_transactions ON users_transactions.transaction_id = transactions.id AND users_transactions.user_id=? WHERE DATE(transactions.created_at)= CURDATE()"); // prepare the sql statement
        $stmt->bind_param('i', $id); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
    }

    public function get_transaction_by_id($id)
    {
        $stmt = $this->connection->prepare("SELECT transactions.id,transactions.item_id,transactions.quantity,transactions.total,items.name,items.price FROM transactions INNER JOIN items ON items.id = transactions.item_id WHERE transactions.id=?"); // prepare the sql statement
        $stmt->bind_param('i', $id); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        $stmt->close();
        // $result = $this->connection->query("SELECT * FROM $this->table WHERE id=$id");
        return $result->fetch_object();
    }
    public function update_transaction($data)
    {
        $item_id = $data['item_id'];
        $item_name = $data['item_name'];
        $quantity = $data['quantity'];
        $total = $data['total'];
        $transaction_id = $data['id'];

        $stmt = $this->connection->prepare("UPDATE $this->table SET item_id=?,item_name=?,quantity=?,total=? WHERE id=?"); // prepare the sql statement
        $stmt->bind_param('ssssi', $item_id, $item_name, $quantity, $total, $transaction_id); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $stmt->close();
    }
    /**
     * update Item_name column in transactions table
     * @param mixed $data
     * @return void
     */
    public function update_itemname($data)
    {
        $item_name = $data['item_name'];
        $transaction_id = $data['id'];

        $stmt = $this->connection->prepare("UPDATE $this->table SET item_name=? WHERE id=?"); // prepare the sql statement
        $stmt->bind_param('si', $item_name, $transaction_id); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $stmt->close();
    }

    //HERE ENDED THE API USED FUNCTIONS
    /**
     * Get all transactions from transation table with corresponding item name form item table using inner Join
     * @return array
     */
    public function get_all_with_item_name()
    {
        $data = array();
        $stmt = $this->connection->prepare("SELECT transactions.id,transactions.item_id,transactions.quantity,transactions.total,transactions.created_at,transactions.updated_at,items.name FROM transactions INNER JOIN items ON items.id = transactions.item_id"); // prepare the sql statement
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
    }
}
