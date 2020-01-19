<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Reset User Password
 * @TODO  Send Confirmation Email and send user notification
 *        that the password has been changed
 * 
 * @FIX Redirect to home page if EMAIL_ENABLED==false
 */
class reset_password extends Controller
{
  public function index()
  {
    if (is_login()) {
      header("Location: /home");
    }
    if(!EMAIL_ENABLED){
      return;
    }
    $this->view("common/head", ["title" => "Change Password", "description" => " Change Password Page"]);
    $this->view("common/header");
    $this->view("home/reset_password");
    $this->view("common/footer");
  }
  public function reset()
  {
    if(!EMAIL_ENABLED){
      return;
    }
    $email =  isset($_POST['email'])?$_POST['email']:"";
    $password =  isset($_POST['password'])?$_POST['password']:"";
    $confirmPassword =  isset($_POST['confirmPassword'])?$_POST['confirmPassword']:"";
    $resetMD =  isset($_POST['resetMD'])?$_POST['resetMD']:"";



    $result = ["code" => 100, "message" => "Unknown Error"];

    if(empty($email)){
      $result = ["code" => 102, "message" => "Email ID Is Required."];
    }else if(empty($password)){
      $result = ["code" => 103, "message" => "Password Is Required."];
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $result = ["code" => 104, "message" => "Email ID Is Not Valid."];
    }else if(strlen($password)<5){
      $result = ["code" => 105, "message" => "Password Must be 5-15 Characters."];
    }else if($password!=$confirmPassword){
      $result = ["code" => 105, "message" => "Password Didn't Match."];
    }else {

      $user_row = db_single("select * from users where email=:email",[
        "email"=>$email
      ]);
      if($user_row)
      {
        $userQuery = "select * from user_reset where email=:email";
        $this->database->query($userQuery);
        $this->database->bind(":email", $email);
        $users = get_user_meta($user_row["userid"],"reset-pwd",false);

        $isAuth=false;
        $time = time();
        foreach($users as $user)
        {
          $user_json = json_decode($user,true);
          $salt = $user_json['key'];
          $resetkey = hash('sha512', $salt.$email);
          if ($resetkey == $resetMD)
          {
            $isAuth = true;
            if($user_json['time'] + 60*30 >= $time) {
              $encryptPassword = md5($password);
              // $userQuery = "update users set password=:password  where email=:email";
              // $this->database->query($userQuery);
              // $this->database->bind(":email", $email);
              // $this->database->bind(":password", $encryptPassword);
              // $this->database->execute();
              db_update("users",[
                "password"=>$encryptPassword
              ],[
                "email"=>$email
              ]);
              $result = ["code" => 1, "message" => "Password changed."];
              delete_user_meta($user_row["userid"],"reset-pwd");
            }else{
              $result = ["code" => 106, "message" => "Token Expired."];
            }
            break;
          }
        }
        if(!$isAuth){
          $result = ["code" => 107, "message" => "User Not Found."];
        }
      }else{
        $result = ["code" => 108, "message" => "Email not registered."];
      }
    }
    echo json_encode($result);
  }
}
