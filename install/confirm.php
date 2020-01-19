<?php
$result  =  ["code"=>100,"message"=>"Unknown error"];
// $result["post"] = $_POST;
$db_info = $_POST["db"];
$website_info = $_POST["website"];
$result["db"]=$db_info;
$result["info"]=$website_info;
$str_array = [];
$str_array[] = "<?php";
$str_array[] = "define('DEBUG', false);";
$str_array[] = "define('DB_HOST', '".$db_info["db_host"]."');";
$str_array[] = "define('DB_USER', '".$db_info["db_user"]."');";
$str_array[] = "define('DB_PASS', '".$db_info["db_pass"]."');";
$str_array[] = "define('DB_NAME', '".$db_info["db_name"]."');";
$str_array[] = "define('POST_PER_PAGE', '".$website_info["postperpage"]."');";
$str_array[] = "define('WEBSITE_NAME', '".$website_info["websitename"]."');";
$file = fopen("../app/tools/default/Variable.php","w");
$str = implode("\n",$str_array);
fwrite($file,$str);

$db_name = $db_info["db_name"];
$db_user = $db_info["db_user"];
$db_pass = $db_info["db_pass"];
$db_host = $db_info["db_host"];
$result  =  ["code"=>100,"message"=>"Unknown error"];
$connection = @new mysqli ($db_host, $db_user, $db_pass,$db_name);

$sql_array = [];
if ($connection->connect_error) {
  $result["message"] = "Connection failed: " . $connection->connect_error;
}else{
  if(isset($website_info["force"]) && $website_info["force"]=="1"){
    $sql_array[]="DROP TABLE IF EXISTS `options`";
    $sql_array[]="DROP TABLE IF EXISTS `posts`";
    $sql_array[]="DROP TABLE IF EXISTS `posts_meta`";
    $sql_array[]="DROP TABLE IF EXISTS `users`";
    $sql_array[]="DROP TABLE IF EXISTS `users_meta`";
  }
  $sql_array[]="CREATE TABLE `options` (
    `metaid` int(11) NOT NULL,
    `meta_key` varchar(500) NOT NULL,
    `meta_value` varchar(20000) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $sql_array[]="CREATE TABLE `posts` (
      `postid` int(11) NOT NULL,
      `userid` int(11) NOT NULL,
      `title` varchar(200) NOT NULL,
      `content` varchar(10000) NOT NULL,
      `tag` int(11) NOT NULL DEFAULT '1',
      `enabled` int(11) NOT NULL DEFAULT '1',
      `time_created` int(11) NOT NULL,
      `post_type` varchar(20) NOT NULL,
      `parentid` int(11) DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      $sql_array[]="CREATE TABLE `posts_meta` (
        `metaid` int(11) NOT NULL,
        `postid` int(11) NOT NULL,
        `meta_key` varchar(1000) NOT NULL,
        `meta_value` mediumtext NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $sql_array[]="CREATE TABLE `users` (
          `userid` int(11) NOT NULL,
          `name` varchar(200) NOT NULL,
          `email` varchar(200) NOT NULL,
          `password` varchar(500) NOT NULL,
          `status` varchar(50) NOT NULL,
          `image` varchar(500) NOT NULL,
          `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `is_admin` varchar(50) NOT NULL,
          `refer_code` varchar(10) NOT NULL,
          `account_type` varchar(100) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
          $sql_array[]="CREATE TABLE `users_meta` (
            `metaid` int(11) NOT NULL,
            `userid` int(11) NOT NULL,
            `meta_key` varchar(500) NOT NULL,
            `meta_value` varchar(20000) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $sql_array[]="ALTER TABLE `options`
            ADD PRIMARY KEY (`metaid`)";
            $sql_array[]="ALTER TABLE `posts`
            ADD PRIMARY KEY (`postid`)";
            $sql_array[]="ALTER TABLE `posts_meta`
            ADD PRIMARY KEY (`metaid`)";
            $sql_array[]="ALTER TABLE `users`
            ADD PRIMARY KEY (`userid`)";
            $sql_array[]="ALTER TABLE `users_meta`
            ADD PRIMARY KEY (`metaid`)";
            $sql_array[]="ALTER TABLE `options`
            MODIFY `metaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5";
            $sql_array[]="ALTER TABLE `posts`
            MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116";
            $sql_array[]="ALTER TABLE `posts_meta`
            MODIFY `metaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1657";
            $sql_array[]="ALTER TABLE `users`
            MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6";
            $sql_array[]="ALTER TABLE `users_meta`
            MODIFY `metaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75";
            $result["message"] = [];
            /* disable autocommit */

            $connection->autocommit(false);
            //$connection->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
            $error_occured  = false;
            $error_count = 0;
            foreach($sql_array as $sql){
              if($error_count<5){
                $res = $connection->query($sql);
                if($res===TRUE){
                  $result["message"][]="Added Table Successfully";
                }else{
                  $error_occured = true;
                  $error_count++;
                  $result["message"][]="Error: ".$connection->error;
                }
              }
            }
            if($error_occured){
              /* Rollback */
              $connection->rollback();
            }else{
              $connection->commit();
            }
            $result["tables"] = $result["message"];
            if(!$error_occured){
              copy("../htaccess_sample.txt","../.htaccess");
              $result["code"]=1;
              $result["message"]="Installed Successfully";
              $result["hasHtaccess"] = file_exists("../.htaccess");
              $result["hasVariable"] = file_exists("../app/tools/default/Variable.php");
            }else{
              $result["message"]=implode("<br/>",$result["message"]);
              $result["post"]=$_POST;
            }
            $connection->close();

          }
          echo json_encode($result);
