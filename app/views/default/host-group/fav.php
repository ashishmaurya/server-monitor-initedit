<?php
$limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;

$fav_list = get_user_meta(current_userid(),"host-group-fav",false,[]);
$fav_list_str = implode(",",$fav_list);

$posts = db_result("select * from posts where enabled=1 and postid in (:postids) and post_type=:post_type and userid=:userid order by postid $limit",[
  "userid"=>current_userid(),
  "post_type"=>'host-group',
  "postids"=>$fav_list_str
]);
get_post_view($posts);
 ?>
