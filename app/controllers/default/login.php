<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Allows users to login
 * @TODO   Add Remember Me functionality
 */

class login extends Controller {
    /*
     * @AUTHOR Ashish
     * @ABOUT Allows users to login
     * @TODO   Show Website name in login header and change background color
     */

    public function index() {
        if (is_login()) {
            if (isset($_GET['redirect'])) {
                header("Location: " . $_GET['redirect']);
            } else {
                header("Location: /home");
            }
        }
        $this->view("common/head", ["title" => "Login", "description" => "Login Page"]);
        $this->view("common/header-login");
        $this->view("home/login");
        $this->view("common/footer");
    }

    /*
     * @AUTHOR Ashish
     * @ABOUT Validates users input credentials
     * @TODO   Redirect to another page if already logged in or show some warning
     * @FIXED  already log in issue fixed
     */

    public function validate() {


        $email = value_post("email", "");
        $password = value_post("password", "");
        $regID = value_post("regId");
        $result = ["code" => 100, "message" => "Unknown Error"];
        if (is_login()) {
            $result["message"] = "You are already logged in";
        } else if (empty($email)) {
            $result = ["code" => 102, "message" => "Email is required"];
        } else if (empty($password)) {
            $result = ["code" => 103, "message" => "Password is required"];
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = ["code" => 104, "message" => "Email is invalid"];
        } else if (strlen($password) < 5) {
            $result = ["code" => 105, "message" => "Password must be 5-15 character"];
        } else {
            $encryptPassword = md5($password);
            $user = db_single("select * from users where email=:email and password=:password", [
                "email" => $email,
                "password" => $encryptPassword
                    ]
            );

            if ($user) {
                $title = "";
                $message = "";
                if (is_account_active($user)) {
                    $title = "Account : Logged in Successfully";
                    $message = "You logged in on " . time_detailed(time());
                    $userID = $user['userid'];
                    $userName = $user['name'];
                    set_session("userid", $userID);
                    set_session("email", $email);
                    set_session("name", $userName);
                    $result = ["code" => 1, "message" => "Successfully Logged In"];
                } else {
                    $title = "Account : Log in denied";
                    $message = "Because your account was inactive " . time_detailed(time());
                    $result = ["code" => 107, "message" => "Account is inactive."];
                }
                add_user_activity($user["userid"], $title, $message, 2);
            } else {
                $result = ["code" => 106, "message" => "Email Or Password is wrong."];
            }
        }

        echo json_encode($result);
    }

}
