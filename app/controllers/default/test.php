<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Test Class to test some random commands or query
 * @TODO   Remove Before Deployment
 */

class test extends Controller {

    public function index() {
    
        
//        $res = add_user_notification(6, "Dummy Message","This is a dummp Message");
//        $res = add_user_notification(6, "Dummy Message testing","This is a dummp Message");
        $res = add_user_notification(6, "<label class='status Online circle'></label>Hello World, Dummy","This is a dummp Message");
        echo "<br/>Okay Okay";
    }
    
}
