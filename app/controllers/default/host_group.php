<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Shows Host Group Information
 * @TODO   Nothing
 * @FIX    Fix title 
 */
class host_group extends Controller {

    public function index() {
        login_require();
        $this->view("common/head", ["title" => "All Host Group", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host-group/all", []);
        $this->view("common/footer");
    }

    public function add() {
        login_require();
        $this->view("common/head", ["title" => "Add New Host Group", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host-group/add", []);
        $this->view("common/footer");
    }

    public function recent() {
        login_require();
        $this->view("common/head", ["title" => "Recently Added Host Group", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host-group/recent", []);
        $this->view("common/footer");
    }

    public function fav() {
        login_require();
        $this->view("common/head", ["title" => "Fav Host Group", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host-group/fav", []);
        $this->view("common/footer");
    }

    public function detail() {
        login_require();
        $this->view("common/head", ["title" => "Home Group Detail", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $postid = value_get("id", -1);
        $post = get_post($postid);
        $this->view("host-group/detail", ["post" => $post]);
        $this->view("common/footer");
    }

    public function edit() {
        login_require();
        $postid = value_get("id", -1);
        $post = get_post($postid);

        $this->view("common/head", ["title" => "Home Group Detail", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host-group/edit", ["post" => $post]);
        $this->view("common/footer");
    }

}
