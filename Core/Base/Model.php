<?php

namespace Core\Base;

class Model
{
    public $connection;
    protected $table;

    public function __construct()
    {
        $this->connection(); // connection is ready
        $this->relate_table();
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * Get all table data from database
     *
     * @return array
     */
    public function get_all(): array
    {
        $data = array();
        $stmt = $this->connection->prepare("SELECT * FROM $this->table"); // prepare the sql statement
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

    /**
     * Get record BY id from Database
     *
     * 
     * 
     */
    public function get_by_id($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE id=?"); // prepare the sql statement
        $stmt->bind_param('i', $id); // bind the params per data type (https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
        $stmt->execute(); // execute the statement on the DB
        $result = $stmt->get_result(); // get the result of the execution
        $stmt->close();
        return $result->fetch_object();
    }

    /**
     * Delete selected record from Database
     *
     * 
     * 
     */
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM $this->table WHERE id=?"); // prepare the sql statement
        $stmt->bind_param('i', $id); // bind the params per data type
        $stmt->execute(); // execute the statement on the DB
        $stmt->close();
        // $result = $this->connection->query("DELETE FROM $this->table WHERE id=$id");
    }

    /**
     * Insert data to table in DateBase
     *
     * @param [type] $data
     * 
     */
    public function create($data)
    {
        // Get dynamic keys title, contenta
        // $keys: string
        // Get dynamic values coresponds to the key '$data->title','$data->content'
        // $values: string

        $keys = '';
        $values = '';
        $data_types = '';
        $value_arr = array();

        foreach ($data as $key => $value) {
            if ($key != \array_key_last($data)) {
                $keys .= $key . ', ';
                $values .= "?, ";
            } else {
                $keys .= $key;
                $values .= "?";
            }

            switch ($key) {
                case 'id':
                case 'user_id':
                case 'item_id':
                case 'transaction_id':
                    $data_types .= "i";
                    break;

                default:
                    $data_types .= "s";
                    break;
            }

            $value_arr[] = $value;
        }

        // $stmt = $this->connection->prepare("INSERT INTO posts (title, content, user_id) VALUES (?,?,?)");
        // $stmt->bind_param('ssi', 'test sql in.', 'testing content', '1');
        // $stmt->execute();
        // $stmt->close();

        $sql = "INSERT INTO $this->table ($keys) VALUES ($values)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($data_types, ...$value_arr); // ...$value_arr == 'test sql in.', 'testing content', '1'
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Update table record data in the DB
     *
     * @param [type] $data
     * 
     */
    public function update($data)
    {
        $set_values = '';
        $id = 0;
        $data_types = '';
        $value_arr = array();

        foreach ($data as $key => $value) {
            if ($key == 'id') {
                $id = $value;
                continue;
            }
            if ($key != \array_key_last($data)) {
                $set_values .= "$key=?, ";
            } else {
                $set_values .= "$key=?";
            }

            switch ($key) {
                case 'id':
                case 'user_id':
                case 'item_id':
                case 'transaction_id':
                    $data_types .= "i";
                    break;

                default:
                    $data_types .= "s";
                    break;
            }

            $value_arr[] = $value;
        }

        // $stmt = $this->connection->prepare("INSERT INTO posts (title, content, user_id) VALUES (?,?,?)");
        // $stmt->bind_param('ssi', 'test sql in.', 'testing content', '1');
        // $stmt->execute();
        // $stmt->close();

        $sql = "UPDATE $this->table SET $set_values WHERE id=$id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($data_types, ...$value_arr); // ...$value_arr == 'test sql in.', 'testing content', '1'
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Establish the connection to DB
     * 
     */
    protected function connection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "pos";

        // Create connection
        $this->connection = new \mysqli($servername, $username, $password, $database);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    /**
     * To get the table name in the DB depending on the class name (Dynamic include tables)
     * 
     */
    protected function relate_table()
    {
        $table_name = \get_class($this);
        $table_name_arr = \explode('\\', $table_name);
        $class_name = $table_name_arr[\array_key_last($table_name_arr)]; // $table_name_arr[2]
        $final_clas_name = \strtolower($class_name) . "s";
        $this->table = $final_clas_name;
    }
}
