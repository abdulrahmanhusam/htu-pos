<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Base\View;
use Core\Helpers\Helper;
use Core\Model\User;

class Authentication extends Controller
{
        private $user = null;

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
         * Displays login form
         *
         *  @return void
         */
        public function login()
        {
                $this->view = 'login';
        }

        /**
         * Login Validation
         *
         *  @return void
         */
        public function validate()
        {

                // if user doesn't exists, do not authenticate
                $user = new User();
                $logged_in_user = $user->check_username($_POST['username']);

                if (!$logged_in_user) {
                        $this->invalid_redirect();
                }

                // $2y$10$jZ.Wc1DszU3G.N3/GdBZ8e.HWtp0GPNTui76M.hGyll2bkxte5Tgi
                // => Decrypt (Unknown)
                // => 1234567
                // => 1234567 === $_POST['password']?
                // Return => true/false
                if (!\password_verify($_POST['password'], $logged_in_user->password)) {
                        $this->invalid_redirect();
                }




                if (isset($_POST['remember_me'])) {
                        // DO NOT ADD USER ID TO THE COOKIES - SECURITY BREACH!!!!!
                        \setcookie('user_id', $logged_in_user->id, time() + (86400 * 30)); // 86400 = 1 day (60*60*24)
                }

                $_SESSION['user'] = array(
                        'username' => $logged_in_user->username,
                        'display_name' => $logged_in_user->display_name,
                        'user_id' => $logged_in_user->id,
                        'is_admin_view' => true,
                        'user_img' => $logged_in_user->image
                );


                if (Helper::check_permission(['item:read', 'user:read', 'transaction:read', 'sales:all'])) {
                        Helper::redirect('/dashboard');
                } elseif (Helper::check_permission(['item:read'])) {
                        Helper::redirect('/items');
                } elseif (Helper::check_permission(['sales:all'])) {
                        Helper::redirect('/sales');
                } elseif (Helper::check_permission(['transaction:read'])) {
                        Helper::redirect('/transactions');
                }
                /*       else{
                        http_response_code(404);
                        new View('404');
                } */
        }

        /**
         * Logout signed in user destroy the session and cookie if is set
         *
         *  @return void
         */
        public function logout()
        {
                \session_destroy();
                \session_unset();
                \setcookie('user_id', '', time() - 3600); // destroy the cookie by setting a past expiry date
                Helper::redirect('/');
        }

        /**
         * If entered wrong data user or pass
         *
         *  @return void
         */
        private function invalid_redirect()
        {
                $_SESSION['error'] = "Invalid Username or Password";
                Helper::redirect('/');
                exit;
        }
}
