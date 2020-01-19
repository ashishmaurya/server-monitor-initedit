<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Display information
 * @TODO   Remove this class
 */
class info extends Controller {

    public function index() {
        if (DEBUG) {
            phpinfo();
        }
    }

}
