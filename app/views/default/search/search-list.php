<?php
$posts = $data["post"];
if(count($posts)==0){
  echo "<h1>Nothing was found</h1>";
}else{
  $f = $posts[0];
  $view_name = "";
  $post_type = $f["post_type"];
  if($post_type=="website"){
    $view_name = "host-list";
  }else if($post_type=="host-group"){
    $view_name = "host-group-list";
  }else if($post_type=="service"){
    $view_name = "service-list";
  }else if($post_type=="url-monitoring"){
    $view_name = "url-list";
  }
  get_post_view($posts,$view_name);
}
