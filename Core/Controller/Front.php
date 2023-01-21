<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;

class Front extends Controller
{
        public function render()
        {
                if (!empty($this->view))
                        $this->view();
        }

        function __construct()
        {
                $this->admin_view(false);
                if (isset($_SESSION['user'])) {
                        if (Helper::check_permission(['item:read', 'user:read', 'transaction:read', 'sales:all'])) {
                                Helper::redirect('/dashboard');
                        } elseif (Helper::check_permission(['item:read'])) {
                                Helper::redirect('/items');
                        } elseif (Helper::check_permission(['sales:all'])) {
                                Helper::redirect('/sales');
                        } elseif (Helper::check_permission(['transaction:read'])) {
                                Helper::redirect('/transactions');
                        }
                }
        } 

        /**
         * Displays the landing page
         *  @return void
         */
        public function index()
        {
               
                $this->view = 'home';

        }
}
