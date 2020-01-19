<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Dashboard page to show latest features
 * @TODO   Add More features like search into dashboard
 */
class dashboard extends Controller
{

    /*
     * @AUTHOR Ashish
     * @ABOUT Shows default dashboard 
     * @TODO   Nothing
     */
    public function index()
    {
        login_require();
        $this->view("common/head", ["title" => "Dashboard","description"=>" Home Page"]);
        $this->view("common/header");
        $this->view("dashboard/home");
        $this->view("common/footer");
    }
}
