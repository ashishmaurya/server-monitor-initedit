<?php

/*
 * @AUTHOR ashishmaurya@outlook.com
 * @TODO Handle Error Page and also add donate button
 *       Allows user to reset password within account page
 * 
 */

class account extends Controller {

    public function index() {
        login_require("/account");
        $this->view("common/head", ["title" => "My Account", "description" => "My Account"]);
        $this->view("common/header", []);
        $this->view("account/home");
        $this->view("common/footer");
    }

    public function update_password() {
        login_require("/account/update-password");
        $this->view("common/head", ["title" => "Change Password", "description" => "My Account"]);
        $this->view("common/header", []);
        $this->view("account/update-password");
        $this->view("common/footer");
    }

    public function edit() {
        login_require("/account/edit");
        $this->view("common/head", ["title" => "Edit My Account", "description" => "My Account"]);
        $this->view("common/header", []);
        $this->view("account/edit");
        $this->view("common/footer");
    }

    public function notification() {
        login_require("/account/notification");
        $this->view("common/head", ["title" => "Notification", "description" => "My Account"]);
        $this->view("common/header", []);
        $this->view("account/notification");
        $this->view("common/footer");
    }

    public function activity() {
        login_require("/account/activity");
        $this->view("common/head", ["title" => "Activity", "description" => "My Account"]);
        $this->view("common/header", []);
        $this->view("account/activity");
        $this->view("common/footer");
    }

    /*
     * @AUTHOR ashishmaurya@outlook.com
     * @TODO Filter and clean name,emailid and theme name
     * @FIXED Added filter and cleaned name,email and theme name
     * 
     */

    public function updatebasic() {
        $result = ["code" => 100, "message" => "Unknown Error"];
        $name = value_post("name");
        $email = value_post("email");
        $theme = value_post("theme", "lighttheme");

        $name = strip_tags($name);
        $theme = strip_tags($theme);

        if (!is_login()) {
            $result["message"] = "Login required to update account";
        } else if (empty($name)) {
            $result["message"] = "Full Name Is Required.";
        } else if (empty($email)) {
            $result["message"] = "Email Id is required.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result["message"] = "Email ID Is Not Valid.";
        } else if (count($name) > 30) {
            $result["message"] = "Name is too large";
        } else {
            $updated = db_update("users", [
                "name" => $name,
                "email" => $email
                    ], [
                "userid" => current_userid()
            ]);
            if ($updated) {
                set_session("name", $name);
                set_session("email", $email);
                update_user_meta(current_userid(), "theme", $theme);

                $result["code"] = 1;
                $result["message"] = "Updated Successfully.";
            } else {
                $result["message"] = "Unable to update";
            }
        }
        echo json_encode($result);
    }

    /*
     * @AUTHOR ashishmaurya@outlook.com
     * @TODO redirect user to login page if not logged in
     */

    public function clearnotification() {
        $userid = current_userid();
        if (is_positive($userid)) {
            update_user_meta($userid, "notification-count", 0);
            update_user_meta($userid, "notification-last-seen", time());
        }
        $response = [
            "code" => 1,
            "message" => "Notification Cleared",
        ];
        echo json_encode($response);
    }

    public function edit_password() {
        $oldpassowrd = value_post("old-password");
        $newpassowrd = value_post("new-password");
        $newConfirmpassowrd = value_post("new-confirm-password");

        if (!is_login()) {
            $result["message"] = "Login required to update account";
        } else if (empty($oldpassowrd)) {
            $result["message"] = "Old Password is required";
        } else if (empty($newpassowrd)) {
            $result["message"] = "New Password is required";
        } else if ($newpassowrd != $newConfirmpassowrd) {
            $result["message"] = "Password didn't match";
        } else {
            $encryptPassword = md5($oldpassowrd);
            $newencryptPassword = md5($newpassowrd);

            $user = db_single("select * from users where userid=:userid and password=:password", [
                "userid" => current_userid(),
                "password" => $encryptPassword
                    ]
            );
            if ($user) {
                $updated = db_update("users", [
                    "password" => $newencryptPassword
                        ], [
                    "userid" => current_userid()
                ]);
                if ($updated) {
                    $result["code"] = 1;
                    $result["message"] = "Updated Successfully.";
                } else {
                    $result["message"] = "Unable to update password";
                }
            } else {
                $result["message"] = "Old Password is wrong";
            }
        }

        echo json_encode($result);
    }

}
