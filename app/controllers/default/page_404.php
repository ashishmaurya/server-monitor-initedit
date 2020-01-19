<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Used to display error page
 * @TODO   Add More Errors
 */

class page_404 extends Controller {

    public function index() {
        $this->view("common/head", ["title" => "Page not found", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("error/404");
        $this->view("common/footer");
    }

}
