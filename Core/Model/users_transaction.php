<?php

namespace Core\Model;

use Core\Base\Model;

class users_transaction extends Model
{
    public function delete_by_transaction_id($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM $this->table WHERE transaction_id=?"); // prepare the sql statement
        $stmt->bind_param('i', $id); // bind the params per data type
        $stmt->execute(); // execute the statement on the DB
        $stmt->close();
    }
}
