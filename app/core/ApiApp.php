<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiApp
 *
 * @author home
 */
class ApiApp 
{
    protected $controller = "home";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
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
        if(!isset($url[0])) {
            $url[0]="vl";
        }
        if(!isset($url[1])) {
            $url[1]="";
        }
        $apiController = $url[0] ."/".$url[1];
        
        if (file_exists("../app/controllers/api/" . $apiController . ".php")) {
            $this->controller = $url[1];
            unset($url[0]);
            unset($url[1]);
        }

        require_once "../app/controllers/api/" . $apiController . ".php";

        $this->controller = new $this->controller;

        if (isset($url[2])) {
            if (method_exists($this->controller, $url[2])) {
                $this->method = $url[2];
                unset($url[2]);

            }
        }
        $this->params = $url ? array_values($url) : [];

        //call_user_func([$this->controller,$this->method],$this->params);
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = explode("/", filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
            if(isset($url[0])) {
                unset($url[0]);
            }
            $url = array_values($url);
            return $url;
        }
    }
}