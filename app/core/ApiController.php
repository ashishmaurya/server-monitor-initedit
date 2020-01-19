<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiController
 *
 * @author home
 */
class ApiController 
{
    public $database;
    public $API_TOKEN_DURATION ;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->database = new Database();
        $this->API_TOKEN_DURATION = 60*60*24*30*3;
    }

    public function model($model)
    {
        require_once "../app/models/api/".$model.".php";
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
        include "../app/views/api/".$view.".php";

    }

    public function loadController($controllerPath)
    {
        if (file_exists("../app/controllers/api/" . $controllerPath . ".php")) {
            require_once "../app/controllers/api/" . $controllerPath . ".php";
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
        if (file_exists("../app/tools/api/" . $controllerPath . ".php")) {
            require_once "../app/tools/api/" . $controllerPath . ".php";
        }else{
            return new Exception("tools Class Not Found.");
        }
    }


    public function error($ERROR_NO = 404,$ERROR_REASON = "")
    {
        $this->view("common/error",["error_no"=>$ERROR_NO,"error_reason"=>$ERROR_REASON]);
    }
}