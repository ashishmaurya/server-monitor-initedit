<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
<?php
$limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;

$fav_list = get_user_meta(current_userid(),"url-fav",false,[]);
$fav_list_str = implode(",",$fav_list);

$base_query = "select * from posts where enabled=:enabled and postid in (:postids) and post_type=:post_type and userid=:userid";
$params = [
  "enabled"=>1,
  "userid"=>current_userid(),
  "post_type"=>'url-monitoring',
  "postids"=>$fav_list_str,
];

$posts = db_result("$base_query order by postid desc $limit",$params);
get_post_view($posts,"url-list");
$counts = db_select("select count(*) as c from ($base_query) as dummy",$params);
$total = $counts[0]["c"];

get_pagination($total);

 ?>

</div>
</div>
</div>
