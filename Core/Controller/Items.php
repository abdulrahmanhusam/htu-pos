<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Tests;
use Core\Model\Item;

class Items extends Controller
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

    public function index()
    {
        $this->permissions(['item:read']);
        $this->view = 'items/index';
        $items = new Item();
        $this->data['items'] = $items->get_all();
        $this->data['items_count'] = count($items->get_all());
    }
    public function single()
    {
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        $this->permissions(['item:read']);
        $this->view = 'items/single';
        $item = new Item();
        $this->data['item'] = $item->get_by_id($_GET['id']);
    }

    /**
     * Display the HTML form for item creation
     *
     * @return void
     */
    public function create()
    {
        $this->permissions(['item:create']);
        $this->view = 'items/create';
    }

    /**
     * Creates new Item
     *
     * @return void
     */
    public function store()
    {
        $this->permissions(['item:create']);
        self::check_if_field_empty($_POST, "/items/create"); // form back-end validation
        $item = new Item();
        // $_POST['user_id'] = $_SESSION['user']['user_id']; // this is the id of the current logged in user. Because the post creator would be the same logged in user.
        $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
        $item->create($data);
        Helper::redirect('/items'); //must go to the item sigle for more accurate
    }

    /**
     * Display the HTML form for item update
     *
     * @return void
     */
    public function edit()
    {
        $this->permissions(['item:read', 'item:update']);
        $this->view = 'items/edit';
        $item = new Item();
        $this->data['item'] = $item->get_by_id($_GET['id']);
    }

    /**
     * Updates the item
     *
     * @return void
     */
    public function update()
    {
        $this->permissions(['item:read', 'item:update']);
        self::check_if_field_empty($_POST, "/items/edit?id=" . $_POST['id']); // form back-end validation
        $item = new Item();
        $data = self::escape_xss_attacks($_POST); //escape Cross Site Scripting using htmlspecialchars()
        $item->update($data);
        Helper::redirect('/item?id=' . $_POST['id']);
    }

    /**
     * Delete the item
     *
     * @return void
     */
    public function delete()
    {
        $this->permissions(['item:read', 'item:delete']);
        $item = new Item();
        $item->delete($_GET['id']);
        Helper::redirect('/items');
    }
}
