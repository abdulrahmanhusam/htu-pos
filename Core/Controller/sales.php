<?php

namespace Core\Controller;

use Core\Base\Controller;


class sales extends Controller
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

        public function index()
        {
                $this->permissions(['sales:all']);
                $this->view = "selling-dashboard";
        }
}
