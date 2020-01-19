<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 2/5/2016
 * Time: 1:11 PM
 */
class App {

    protected $controller = "home";
    protected $method = "index";
    protected $params = [];

    public function __construct() {
        if (!SessionManagement::sessionExists("userid") && CookieManagment::getCookie("remember") == "true") {
            $loginInfo = CookieManagment::getCookie("loginInfo");
            $controller = new Controller();
            $userObject = $controller->loadController("user");
            $userDetailObject = $userObject->getUserByLoginInfo($loginInfo);
            SessionManagement::sessionStart();
            SessionManagement::setSession("userid", $userDetailObject->getUserid());
            SessionManagement::setSession("img", $userDetailObject->getUserImage());
            SessionManagement::setSession("username", $userDetailObject->getUsername());
        }

        $url = $this->parseUrl();

        if (isset($url[0])) {
            $url[0] = str_replace("-", "_", $url[0]);
        }

        if (file_exists("../app/controllers/default/" . $url[0] . ".php")) {
            $this->controller = $url[0];
            unset($url[0]);
        } else if (!empty($url[0])) {
            $this->controller = "page_404";
        }
        require_once "../app/controllers/default/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            $url[1] = str_replace("-", "_", $url[1]);
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $url[1] = str_replace("_", "-", $url[1]);
            }
        }
        $this->params = $url ? array_values($url) : [];

        //call_user_func([$this->controller,$this->method],$this->params);
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return $url = explode("/", filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
        }
    }

}
