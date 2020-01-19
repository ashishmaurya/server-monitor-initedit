<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 2/5/2016
 * Time: 1:11 PM
 */
class AdminApp
{
    protected $controller = "home";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        if(!isset($url[0])) {
            $url[0]="";
        }
        if (file_exists("../app/controllers/admin/" . $url[0] . ".php")) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once "../app/controllers/admin/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);

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