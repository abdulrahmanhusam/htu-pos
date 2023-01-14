<?php

namespace Core\Controller;

use Core\Base\Controller;


class Front extends Controller
{
        public function render()
        {
                if (!empty($this->view))
                        $this->view();
        }
/* 
        function __construct()
        {
                $this->auth();
                $this->admin_view(false);
        } */

        /**
         * Displays the landing page
         *  @return void
         */
        public function index()
        {
               
                $this->view = 'home';

        }
}
