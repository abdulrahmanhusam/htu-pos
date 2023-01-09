<?php

namespace Core\Base;


class View
{
    /**
     * Dynamic including views 
     * @return void
     */
    public function __construct(string $view, array $data = array())
    {

        $data = (object) $data;

        $header = 'header';
        $footer = 'footer';

        if (isset($_SESSION['user']['is_admin_view'])) {
            if ($_SESSION['user']['is_admin_view']) {
                $header = 'header-admin';
                $footer = 'footer-admin';
            }
        }


        include_once \dirname(__DIR__, 2) . "/resources/views/partials/$header.php"; // includes the header for all the views

        include_once \dirname(__DIR__, 2) . "/resources/views/$view.php";

        include_once \dirname(__DIR__, 2) . "/resources/views/partials/$footer.php"; // include the footer for all the views
    }
}
