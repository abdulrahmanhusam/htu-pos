<?php

session_start();

use Core\Model\User;
use Core\Router;

spl_autoload_register(function ($class_name) {
    str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
    $class_name = str_replace("\\", '/', $class_name); // \\ = \
    $file_path = __DIR__ . "/" . $class_name . ".php";
    require_once $file_path;
});

if (isset($_COOKIE['user_id']) && !isset($_SESSION['user'])) { // check if there is user_id cookie.
    // log in the user automatically
    $user = new User(); // get the user model
    $logged_in_user = $user->get_by_id($_COOKIE['user_id']); // get the user by id
    $_SESSION['user'] = array( // set up the user session that idecates that the user is logged in.
        'username' => $logged_in_user->username,
        'display_name' => $logged_in_user->display_name,
        'user_id' => $logged_in_user->id,
        'is_admin_view' => true
    );
}

// This code will run only at the first time of using the app.

Router::get('/', "front.index"); // Display landing page
// For home page (login page)
Router::get('/login', "authentication.login"); // Display home.php
Router::post('/authenticate', "authentication.validate"); // Displays the login form
Router::get('/authenticate', "authentication.validate"); // Displays the login form
Router::get('/logout', "authentication.logout"); // Logs the user out


// athenticated
Router::get('/dashboard', "admin.index"); // Displays the admin dashboard

//                                                  (Items or stocks table routes)
// athenticated + permissions [item:read]
Router::get('/items', "items.index"); // list of items (HTML)
Router::get('/item', "items.single"); // Displays single item (HTML)
// athenticated + permissions [item:create]
Router::get('/items/create', "items.create"); // Display the creation form (HTML)
Router::post('/items/store', "items.store"); // Creates the items (PHP)
// athenticated + permissions [item:read, item:create]
Router::get('/items/edit', "items.edit"); // Display the edit form (HTML)
Router::post('/items/update', "items.update"); // Updates the items (PHP)
// athenticated + permissions [item:read, item:detele]
Router::get('/items/delete', "items.delete"); // Delete the item (PHP)
// Interface that intracts with ajax (Creating a sparated Controller cause a problem after host it HTTP_CODE 500)
Router::get('/sales', "items.sales");
//                                          (Users table routes)
// athenticated + permissions [user:read]
Router::get('/users', "users.index"); // list of users (HTML)
Router::get('/user', "users.single"); // Displays single user (HTML)
// athenticated + permissions [user:create]
if (trim($_SESSION['user']['username']) != 'demo_admin') {
    Router::get('/users/create', "users.create"); // Display the creation form (HTML)
    Router::post('/users/store', "users.store"); // Creates the users (PHP)
// athenticated + permissions [user:read, user:create]
    Router::get('/users/edit', "users.edit"); // Display the edit form (HTML)
    Router::post('/users/update', "users.update"); // Updates the users (PHP)
// athenticated + permissions [user:read, user:delete]
    Router::get('/users/delete', "users.delete"); // Delete the user (PHP)
}

// authenticated Users to  show and update Profile Page
Router::get('/profile', "users.profile"); //display current user Info
if (trim($_SESSION['user']['username']) != 'demo_admin') {
    Router::get('/profile/edit', "users.edit_profile"); //Edit Profile  Info (HTML)
    Router::post('/profile/update', "users.update_profile"); //Update Profile  Info (PHP)
}


// athenticated + permissions [post:read]
Router::get('/transactions', "transactions.index"); // list of transactions (HTML)
Router::get('/transaction', "transactions.single"); // Displays single tag (HTML)
//transaction creation proccess done inside selling dashboard
// athenticated + permissions [tag:read, tag:create]
Router::get('/transactions/edit', "transactions.edit"); // Display the edit form (HTML)
Router::post('/transactions/update', "transactions.update"); // Updates the transactions (PHP)
// athenticated + permissions [tag:read, tag:detele]
Router::get('/transactions/delete', "transactions.delete"); // Delete the tag (PHP)

// Api requests
Router::get('/api/sales', "endpoints.items"); //to get items from items table 

Router::post('/api/sales/create', "endpoints.transaction_create"); // for create transcation using ajax 
Router::post('/api/sales/list', "endpoints.transaction_list"); // list current user transcations using ajax
Router::post('/api/sales/list/single', "endpoints.transaction_list_by_id"); // list selected user transcation details using ajax
Router::put('/api/sales/update', "endpoints.transaction_update"); // update user transcation using ajax 
Router::delete('/api/sales/delete', "endpoints.transaction_delete"); // delete user transcation using ajax

//this route to get items top prices in the admin dashboard
Router::get('/api/sales/topprices', "endpoints.items_top_prices");

Router::redirect();
