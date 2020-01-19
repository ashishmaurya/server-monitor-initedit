<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <?php
      // add_user_notification(current_userid(),"Welcome user","How you doing this time of the month");
      $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
      $posts = db_result("select * from posts where enabled=1 and post_type=:post_type and userid=:userid order by postid $limit",[
        "userid"=>current_userid(),
        "post_type"=>'host-group'
      ]);
      get_post_view($posts,'host-group-list');
      ?>
    </div>
  </div>
</div>
