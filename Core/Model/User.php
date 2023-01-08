<?php

namespace Core\Model;

use Core\Base\Model;
use Core\Helpers\Tests;

class User extends Model
{
    use Tests;

    const ADMIN = array(
        "item:read", "item:create", "item:update", "item:delete",
        "user:read", "user:create", "user:update", "user:delete",
        "transaction:read", "transaction:create", "transaction:update", "transaction:delete",
        "sales:all"

    );

    const SELLER = array(
        "sales:all"
    );
    const PROCUREMENT = array(
        "item:read", "item:create", "item:update",
    );
    const ACCOUNTANT = array(
        "transaction:read", "transaction:update"
    );


    public function check_username(string $username)
    {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE username=?"); // prepare the sql statement
        $stmt->bind_param('s', $username); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        $stmt->close();
        return $result->fetch_object();
    }
    /**
     * Summary of get_permissions
     * @return array
     */
    public function get_permissions()
    {
        $permissions = array();
        self::check_if_exists(isset($_SESSION['user']), "you have to login first");
        $user = $this->get_by_id($_SESSION['user']['user_id']);
        if ($user) {
            // $permissions = \explode(',', $user->permissions);
            $permissions = \unserialize($user->permissions); //retrun permissions as an array.
        }
        return $permissions;
    }
}
