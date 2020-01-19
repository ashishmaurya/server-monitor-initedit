<?php
function value_post($key){
  return isset($_POST[$key])?$_POST[$key]:null;
}
$db_name = value_post("db_name");
$db_user = value_post("db_user");
$db_pass = value_post("db_pass");
$db_host = value_post("db_host");
$result  =  ["code"=>100,"message"=>"Unknown error"];
$connection = @new mysqli ($db_host, $db_user, $db_pass,$db_name);

// Check connection
if ($connection->connect_error) {
  $result["message"] = "Connection failed: " . $connection->connect_error;
}else{
  $result["code"]=1;
  $result["message"] = "Connected to host";
}
echo json_encode($result);
