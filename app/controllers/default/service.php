<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Shows Service Detail
 * @TODO   Add More Functionality like triggers(send email when certain services goes down or reaches some threshhold) 
 */
class service extends Controller {

  public function index()
  {
      login_require();
      $id = value_get("id");
      $service = get_post($id);
      if(!$service){
        header("Location: /");
      }
      $this->view("common/head", ["title" => "Services","description"=>""]);
      $this->view("common/header", ["category" => 'home']);
      $this->view("service/service",["service"=>$service]);
      $this->view("common/footer");
  }
  public function edit()
  {
      login_require();
      $id = value_get("id");
      $service = get_post($id);
      if(!$service){
        header("Location: /");
      }
      $this->view("common/head", ["title" => "Services","description"=>""]);
      $this->view("common/header", ["category" => 'home']);
      $this->view("service/edit",["service"=>$service]);
      $this->view("common/footer");
  }

}
