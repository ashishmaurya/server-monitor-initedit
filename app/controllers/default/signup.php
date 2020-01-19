<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 4/16/2016
 * Time: 2:48 PM
 */
class signup extends Controller {

    public function index() {
        if (is_login()) {
            header("Location: /home");
        }
        $this->view("common/head", ["title" => "Signup", "description" => " Signup Page"]);
        $this->view("common/header-login");
        $this->view("home/signup");
        $this->view("common/footer");
    }

    public function add() {
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
        $referalCode = isset($_POST['referal']) ? $_POST['referal'] : "";

        $result = ["code" => 100, "message" => "Unknown Error"];

        $name = strip_tags($name);
        
        if (is_login()) {
            $result["message"] = "You are already logged in";
        } else if (empty($name)) {
            $result = ["code" => 101, "message" => "Name is required"];
        } else if (empty($email)) {
            $result = ["code" => 102, "message" => "Email is required"];
        } else if (empty($password)) {
            $result = ["code" => 103, "message" => "Password is required"];
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = ["code" => 104, "message" => "Email is invalid."];
        } else if (strlen($password) < 5 && strlen($password)>15) {
            $result = ["code" => 105, "message" => "Password Must be 5-15 charecters long"];
        }else if(strlen($name)>30){
            $result["message"]="Name should be less then 30 chareters";
        }else if(strlen($email)>100){
            $result["message"]="Invalid Email(too long)";
        } else {
            $userQuery = "select count(*) from users where email=:email";
            $this->database->query($userQuery);
            $this->database->bind(":email", $email);
            $alreadyUser = $this->database->firstColumn();
            if ($alreadyUser == 0) {
                $name3 = substr($name, 0, 3);
                $new_referal_code = strtoupper("REFRAL");
                $encryptPassword = md5($password);
                $name = ucwords($name);
                $userid = db_insert("users", [
                  "name" => $name,
                  "email" => $email,
                  "password" => $encryptPassword,
                  "refer_code" => $new_referal_code,
                  "status" => 'Enabled',
                  "image" => '',
                  "is_admin" => 'false',
                  "account_type" => 'Customer',
                ]);
                $title = "Account : Account Created";
                $message = "You have Successfully created a new account";
                add_user_activity($userid,$title,$message,1);


                $lastid = db_insert("posts", [
                  "title" => "default-group",
                  "content" => "A default Host Group",
                  "time_created" => time(),
                  "post_type" => "host-group",
                  "userid" => $userid,
                  "parentid"=>null
                ]);
                add_user_notification($userid,"Welcome, $name","Welcome to server monitoring tool");

                $title = "Host Group : New Host Group Created";
                $message = "New Host Group was created";
                $message.= "<div class='text-center'><a href='/host-group/detail?id=$lastid'>default-group</a></div>";
                add_user_activity($userid,$title,$message,3);

                $result = ["code" => 1, "message" => "Account Created Successfully"];
            } else {
                $result = ["code" => 106, "message" => "Email ID Already Used."];
            }
        }
        echo json_encode($result);
    }

}
