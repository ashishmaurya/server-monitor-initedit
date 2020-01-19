<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Used to redirect to install page when user visits websites for first time
 * @TODO  Nothing
 */
if(!file_exists("app/tools/default/Variable.php")){
  header("Location: /install");
}

