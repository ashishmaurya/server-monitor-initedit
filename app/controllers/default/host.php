<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Shows information related to hosts
 * @TODO   Show Proper Error Page when post not found
 *          
 * @FIX     Show Proper Title
 */

class host extends Controller {

    public function index() {
        login_require();
        $this->view("common/head", ["title" => "All Host", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host/all", []);
        $this->view("common/footer");
    }

    public function edit() {
        login_require();
        $this->view("common/head", ["title" => "Edit Host", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host/edit", []);
        $this->view("common/footer");
    }

    public function add() {
        login_require();
        $this->view("common/head", ["title" => "Add New Host", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host/add", []);
        $this->view("common/footer");
    }

    public function recent() {
        login_require();
        $this->view("common/head", ["title" => "Recently Host", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host/recent", []);
        $this->view("common/footer");
    }

    public function fav() {
        login_require();
        $this->view("common/head", ["title" => "Fav Host", "description" => " Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("host/fav", []);
        $this->view("common/footer");
    }

    /*
     * @AUTHOR Ashish
     * @ABOUT Shows Detailed Host Info and Lists All services
     * @TODO   Redirect to /host when posts not found or show error page
     */

    public function detail() {
        login_require();
        $this->view("common/head", ["title" => "Host info", "description" => " Home Page"]);

        $this->view("common/header", ["category" => 'home']);
        $postid = value_get("id", -1);
        $post = get_post($postid);
        $this->view("host/detail", ["post" => $post]);
        $this->view("common/footer");
    }

}
