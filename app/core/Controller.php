<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 2/5/2016
 * Time: 1:13 PM
 */
class Controller
{
    public $database;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->database = new Database();
    }

    public function model($model)
    {
        require_once "../app/models/default/".$model.".php";
    }
    public function view($view,$data=[])
    {
        //require_once "app/views/".$view.".php";
        include "../app/views/default/".$view.".php";

    }
    public function getview($view,$data=[])
    {
        ob_start();
        //require_once "app/views/".$view.".php";
        include "../app/views/default/".$view.".php";
        $v = ob_get_clean();
        return $v;

    }

    public function loadController($controllerPath)
    {
        if (file_exists("../app/controllers/default/" . $controllerPath . ".php")) {
            require_once "../app/controllers/default/" . $controllerPath . ".php";
            return new $controllerPath;
        }else{
            return new Exception("Controller Class Not Found.");
        }

    }
    public function loadTools($controllerPath)
    {
        if (file_exists("../app/tools/default/" . $controllerPath . ".php")) {
            require_once "../app/tools/default/" . $controllerPath . ".php";
        }else{
            return new Exception("tools Class Not Found.");
        }

    }
    public function loadLib($controllerPath)
    {
        if (file_exists("../app/tools/lib/" . $controllerPath . ".php")) {
            require_once "../app/tools/lib/" . $controllerPath . ".php";
        }else{
            return new Exception("tools Class Not Found.");
        }

    }


    public function error($ERROR_NO = 404,$ERROR_REASON = "")
    {
        $this->view("common/error",["error_no"=>$ERROR_NO,"error_reason"=>$ERROR_REASON]);
    }

}