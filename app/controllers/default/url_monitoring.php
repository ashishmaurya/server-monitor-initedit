<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT URL Monitoring detailed information
 * @TODO   Add More robust interval and url 
 */
class url_monitoring extends Controller {

  public function index() {
    login_require();
    $this->view("common/head", ["title" => "URL Monitoring", "description" => "My Account"]);
    $this->view("common/header", []);
    $this->view("url/home");
    $this->view("common/footer");
  }
  public function add() {
    login_require();
    $this->view("common/head", ["title" => "URL Monitoring", "description" => "My Account"]);
    $this->view("common/header", []);
    $this->view("url/add");
    $this->view("common/footer");
  }
  public function fav() {
    login_require();
    $this->view("common/head", ["title" => "URL Monitoring", "description" => "My Account"]);
    $this->view("common/header", []);
    $this->view("url/fav");
    $this->view("common/footer");
  }
  public function recent() {
    login_require();
    $this->view("common/head", ["title" => "URL Monitoring", "description" => "My Account"]);
    $this->view("common/header", []);
    $this->view("url/recent");
    $this->view("common/footer");
  }
  public function detail()
  {
      login_require();
      $id = value_get("id");
      $service = get_post($id);
      if(!$service){
        header("Location: /url-monitoring");
      }
      $this->view("common/head", ["title" => "URL Monitor","description"=>""]);
      $this->view("common/header", ["category" => 'home']);
      $this->view("url/detail",["post"=>$service]);
      $this->view("common/footer");
  }
  public function edit()
  {
      login_require();
      $id = value_get("id");
      $service = get_post($id);
      if(!$service){
        header("Location: /url-monitoring");
      }
      $this->view("common/head", ["title" => "Edit","description"=>""]);
      $this->view("common/header", ["category" => 'home']);
      $this->view("url/edit",["post"=>$service]);
      $this->view("common/footer");
  }
}
