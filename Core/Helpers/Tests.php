<?php

namespace Core\Helpers;

use Exception;

trait Tests
{
    protected static function check_if_exists($expr, $msg)
    {
        try {
            if (!$expr) {
                throw new \Exception($msg);
            }
        } catch (\Exception $error) {
            echo $error->getMessage();
            die;
        }
    }
    /**
     * static so any class can use it without need to create obj
     * check the request method POST elements if any field empty (BACK-END VALIDATION)
     */
    protected static function check_if_field_empty(array $data, string $redirect_url)
    {
        try {
            $roles_arr = array("admin", "procurement", "seller", "accountant");
            if (empty($data)) { // if input name attr deleted or unset
                throw new Exception("All fields Required");
            }
            foreach ($data as $key => $value) {
                if (is_null($value) || $value == "") {
                    throw new Exception("Please Fill all fields");
                }

                if ($key == "role" && !in_array($value, $roles_arr)) { //check if role not in the select menu 
                    throw new Exception("Invalid Role");
                }
            }
        } catch (\Exception $error) {
            $_SESSION['backend_validation_error'] = $error->getMessage();
            Helper::redirect($redirect_url);
            exit;
        }
    }
    protected static function escape_xss_attacks(array $data)
    {
        $vulnerable_to_attck = array("display_name", "email", "username", "name"); //from users and items tbl
        foreach ($data as $key => $value) {
            if (in_array($key, $vulnerable_to_attck)) {
                $data[$key] = \htmlspecialchars($value);
            }
        }
        return $data;
    }
}
