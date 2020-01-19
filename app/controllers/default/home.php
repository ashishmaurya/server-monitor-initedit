<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Home page(first page that displays)
 * @TODO   Show  a front bussiness page rather then
 *          redirecting to login page
 */

//http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
class home extends Controller {

    public function index() {
        if (is_login()) {
            $this->view("common/head", ["title" => "All Host", "description" => " Home Page"]);
            $this->view("common/header", ["category" => 'home']);
            $this->view("home/home");
            $this->view("common/footer");
        }else{
            $this->view("common/head", ["title" => WEBSITE_NAME, "description" => " Home Page"]);
            $this->view("common/header-login");
            $this->view("home/bussiness");
        }
    }

}
