<?php
$limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
$time = time()-60*60*24*30;

$posts = db_result("select * from posts where enabled=1 and post_type=:post_type and time_created>:time_created and userid=:userid order by postid $limit",[
  "userid"=>current_userid(),
  "post_type"=>'host-group',
  "time_created"=>$time
]);
get_post_view($posts);
 ?>
