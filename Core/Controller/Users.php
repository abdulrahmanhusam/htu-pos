<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Tests;
use Core\Model\User;
use Exception;

class Users extends Controller
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
     * Gets all users
     *
     * @return void
     */
    public function index()
    {
        $this->permissions(['user:read']);
        $this->view = 'users/index';
        $user = new User(); // new model User.
        $this->data['users'] = $user->get_all();
        $this->data['users_count'] = count($user->get_all());
    }

    public function single()
    {
        $this->permissions(['user:read']);
        $this->view = 'users/single';
        $user = new User();
        $this->data['user'] = $user->get_by_id($_GET['id']);
    }

    /**
     * Display the HTML form for User creation
     *
     * @return void
     */
    public function create()
    {
        $this->permissions(['user:create']);
        $this->view = 'users/create';
    }

    /**
     * Creates new User
     *
     * @return void
     */
    public function store()
    {
        $this->permissions(['user:create']);
        self::check_if_field_empty($_POST, "/users/create"); // form back-end validation

        $user = new User();
        // process role
        $permissions = null;
        switch ($_POST['role']) {
            case 'admin':
                $permissions = User::ADMIN;
                break;
            case 'procurement':
                $permissions = User::PROCUREMENT;
                break;
            case 'accountant':
                $permissions = User::ACCOUNTANT;
                break;

            case 'seller':
                $permissions = User::SELLER;
                break;
        }
        unset($_POST['role']);
        // $_POST['permissions'] = implode(',', $permissions);
        $_POST['permissions'] = \serialize($permissions);
        $_POST['password'] = \password_hash($_POST['password'], \PASSWORD_DEFAULT); //for security
        $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
        $user->create($data);
        Helper::redirect('/users');
    }

    /**
     * Display the HTML form for User update
     *
     * @return void
     */
    public function edit()
    {
        $this->permissions(['user:read', 'user:update']);
        $this->view = 'users/edit';
        $user = new User();
        $this->data['user'] = $user->get_by_id($_GET['id']);
    }

    /**
     * Updates the User
     *
     * @return void
     */
    public function update()
    {
        $this->permissions(['user:read', 'user:update']);
        self::check_if_field_empty($_POST, "/users/edit?id=" . $_POST['id']); // form back-end validation
        $user = new User();
        // process role
        $permissions = null;
        switch ($_POST['role']) {
            case 'admin':
                $permissions = User::ADMIN;
                break;
            case 'procurement':
                $permissions = User::PROCUREMENT;
                break;
            case 'accountant':
                $permissions = User::ACCOUNTANT;
                break;

            case 'seller':
                $permissions = User::SELLER;
                break;
        }
        unset($_POST['role']);
        // $_POST['permissions'] = implode(',', $permissions);
        $_POST['permissions'] = \serialize($permissions);
        $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
        $user->update($data);
        Helper::redirect('/user?id=' . $_POST['id']);
    }

    /**
     * Delete the user
     *
     * @return void
     */
    public function delete()
    {
        $this->permissions(['user:read', 'user:delete']);
        $user = new User();
        $user->delete($_GET['id']);
        Helper::redirect('/users');
    }

    /**
     * Display user profile page
     *
     * @return void
     */
    public function profile()
    {

        $this->view = 'profile/index';
        $user = new User;
        $this->data['user'] = $user->get_by_id($_SESSION['user']['user_id']); //get this logged in user INFO
    }
    /**
     * Display EDIT user profile form (HTML)
     *
     * @return void
     */
    public function edit_profile()
    {

        $this->view = 'profile/edit';
        $user = new User;
        $this->data['user'] = $user->get_by_id($_SESSION['user']['user_id']); //get this logged in user INFO
    }

    /**
     * Process Updating user profile info
     *
     * @return void
     */
    public function update_profile()
    {


        $user_id = (int) $_SESSION['user']['user_id'];
        self::check_if_exists(isset($user_id), "Please login");
        self::check_if_field_empty($_POST, "/profile/edit"); // form back-end validation
        //if (!isset($_FILES['image']))//validation isset files and POST
        try {
            //if there is no new image added will not enter here
            if (!empty($_FILES) && isset($_FILES)) {
                //the $_POST ARRAY doesn't contain any files or images it stpred in $_FILES global var arry
                $img_name = $_FILES['image']['name'];
                $img_size = $_FILES['image']['size'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $img_error = $_FILES['image']['error'];

                if ($img_error != 0) {
                    throw new Exception($img_error);
                }
                if ($img_size > 125000) {
                    throw new Exception("Image size more than 1MB"); //125000 bytes =125KB best img size for websites biggeer sizes will made load
                }
                $img_extension = pathinfo($img_name, PATHINFO_EXTENSION); //allowed image types (CHECK file extension)
                $img_extension_lc = strtolower($img_extension);

                $allowed_ext = array("jpg", "jpeg", "png", "raw", "webp");
                if (in_array($img_extension_lc, $allowed_ext)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_extension_lc;
                    $dir = str_replace("\\", "/", __DIR__); // to change \ inside the path
                    $img_upload_path = \dirname($dir, 2) . "/resources/uploads/" . $new_img_name; //path where images stored
                    move_uploaded_file($tmp_name, $img_upload_path); //move the image to the path

                    //Insert the path to the dataBase
                    $user = new User();
                    $data_to_update = array('id' => $user_id, 'image' => $new_img_name);
                    $user->update($data_to_update);
                } else {
                    throw new Exception("Unacceptable File Type");
                }
                //form validation
                $user1 = new User();
                $_POST['id'] = $user_id;
                krsort($_POST); // to remove comma from the last key in array where update stmt
                $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
                $user1->update($data);
                Helper::redirect('/profile');
            }
        } catch (\Exception $error) {
            /*        echo $error->getMessage() . "<br>";
            echo "This page will refresh in Five Seconds"; */
            if ($error->getMessage() != 4) {
                header("Location: /profile/edit?error=" . $error->getMessage());
            } else {
                //form validation
                $user1 = new User();
                $_POST['id'] = $user_id;
                krsort($_POST); // to remove comma from the last key in array where update stmt
                $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
                $user1->update($data);
                Helper::redirect('/profile');
            }
        }
    }
}
