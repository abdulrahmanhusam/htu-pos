<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Model\Item;
use Core\Model\Transaction;
use Core\Model\User;
use Core\Model\users_transaction;

class Admin extends Controller
{
        public function render()
        {
                if (!empty($this->view))
                        $this->view();
        }

        function __construct()
        {
                $this->auth();
                $this->admin_view(true);
        }

        /**
         * Displays the admin dashboard
         *
         *  @return void
         */
        public function index()
        {
                $this->permissions(['item:read', 'user:read', 'transaction:read', 'sales:all']); //must have all these permissions to access the dashboard
                $this->view = 'dashboard';
                $user = new User;
                $this->data['users_count'] = count($user->get_all());
                $item = new Item;
                $this->data['items_count'] = count($item->get_all());
                $transaction = new Transaction(); // new model transaction.
                $this->data['transactions_count'] = count($transaction->get_all());
                $sales = new users_transaction;
                $this->data['sales_count'] = count($sales->get_all());
        }
}
