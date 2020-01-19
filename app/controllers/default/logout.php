<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Allows user to logout
 * @TODO   Nothing
 */
class logout extends Controller {

    public function index() {
        $result = ["code" => 100, "message" => "Unknown Error"];
        $appSession = session_exists("app");
        $regId = value_post("regId");
        $userid = current_userid();
        if (session_destroy()) {
            if ($appSession) {
                SessionManagement::setSession("app", true);
                
            }
            if(!empty($regId))
            {
                delete_user_meta($userid, "fbid", $regId);
            }
            $result = ["code" => 1, "message" => "Logged Out."];
        } else {
            $result = ["code" => 101, "message" => "Unable To Logout."];
        }
        echo json_encode($result);
        
    }

}
