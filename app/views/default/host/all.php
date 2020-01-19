<?php
$status = value_get("status");
?>
<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-4 pb-10">
          <div class="host-name">
            All Hosts &nbsp;&nbsp;
            <a href="/host/add"
            class="btn btn-primary btn-sm">
            Add Host
          </a>
        </div>
      </div>
      <div class="col-sm-8 pb-10">
        <div class="btn-group right">
          <a href="/host/all?status=Online"
           class="btn  <?php echo $status=="Online"?"bg-primary":"btn-default";?>">Online</a>
          <a href="/host/all?status=Offline"
           class="btn  <?php echo $status=="Offline"?"bg-primary":"btn-default";?>">Offline</a>
        </div>
      </div>
    </div>
      <?php
      $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
      $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid";
      $params = [
        "enabled"=>1,
        "userid"=>current_userid(),
        "post_type"=>'website'
      ];
      if(empty($status)){
        $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid";
        $params = [
          "enabled"=>1,
          "userid"=>current_userid(),
          "post_type"=>'website'
        ];
      }else{
        $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid and postid in (
          select postid from posts_meta where meta_value=:meta_value
          )";
        $params = [
          "enabled"=>1,
          "userid"=>current_userid(),
          "post_type"=>'website',
          "meta_value"=>$status
        ];

      }
      $posts = db_result("$base_query order by postid $limit",$params);
      get_post_view($posts);

            $total = db_single("select count(*) as c from ($base_query) as dummy",$params)["c"];
            get_pagination($total);

      ?>
    </div>
  </div>
</div>
