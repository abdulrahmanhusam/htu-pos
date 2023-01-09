<?php

namespace Core\Helpers;

use Core\Model\User;

class Helper
{
    /**
     * Redirect the user to specific Url
     *
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
    }
    /**
     * Check Current logged in user permissions
     *
     * @param array $permissions_set
     * @return boolean
     */
    public static function check_permission(array $permissions_set): bool
    {
        $display = true;

        if (!isset($_SESSION['user'])) {
            return false;
        }

        $user = new User;
        $assigned_permissions = $user->get_permissions();
        foreach ($permissions_set as $permission) {
            if (!in_array($permission, $assigned_permissions)) {
                return false;
            }
        }
        return $display;
    }
    /**
     * Get roles of users in the db 
     *
     * @param object $user
     * @return string
     */
    public static function get_role(object $user)
    {

        $role = '';
        $permissions = array();

        $permissions = \unserialize($user->permissions); //retrun permissions as an array.

        if (in_array("item:read", $permissions) && in_array("sales:all", $permissions)) {
            $role = "Admin";
        } elseif (in_array("item:read", $permissions)) {
            $role = "Procurement manager";
        } elseif (in_array("transaction:read", $permissions)) {
            $role = "Accountant";
        } elseif (in_array("sales:all", $permissions)) {
            $role = "seller";
        }




        return $role;
    }
}
