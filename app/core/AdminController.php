<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 3/1/2016
 * Time: 9:10 PM
 */
class AdminController
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
        require_once "../app/models/admin/".$model.".php";
        //return new $model();
    }
    public function modelDefault($model)
    {
        require_once "../app/models/default/".$model.".php";
        //return new $model();
    }
    public function view($view,$data=[])
    {
        //require_once "app/views/".$view.".php";
        include "../app/views/admin/".$view.".php";

    }

    public function loadController($controllerPath)
    {
        if (file_exists("../app/controllers/admin/" . $controllerPath . ".php")) {
            require_once "../app/controllers/admin/" . $controllerPath . ".php";
            return new $controllerPath;
        }else{
            return new Exception("Controller Class Not Found.");
        }

    }
    public function loadDefaultController($controllerPath)
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
        if (file_exists("../app/tools/admin/" . $controllerPath . ".php")) {
            require_once "../app/tools/admin/" . $controllerPath . ".php";
        }else{
            return new Exception("tools Class Not Found.");
        }

    }


    public function error($ERROR_NO = 404,$ERROR_REASON = "")
    {
        $this->view("common/error",["error_no"=>$ERROR_NO,"error_reason"=>$ERROR_REASON]);
    }
}