<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">

      <?php
      $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
      $time = time()-60*60*24*30;
      $params = [
        "userid"=>current_userid(),
        "post_type"=>'website',
        "time_created"=>$time,
        "enabled"=>1
      ];
      $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and time_created>:time_created and userid=:userid";
      $posts = db_result("$base_query order by postid $limit",$params);
      get_post_view($posts);

      $counts = db_select("select count(*) as c from ($base_query) as dummy",$params);
      $total = $counts[0]["c"];

      get_pagination($total);
      ?>
    </div>
  </div>
</div>
