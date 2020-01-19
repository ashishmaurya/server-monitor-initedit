<?php
$post = $data["post"];
extract($post);
?>

<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <div class="pb-10 bold">
        <?php echo strtoupper($title);?>
      </div>
      <?php
      $parentid = $post["postid"];
      $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
      $posts = db_result("select * from posts where enabled=1 and post_type=:post_type and parentid=:parentid order by postid desc $limit",[
        "parentid"=>$parentid,
        "post_type"=>'website'
      ]);
      get_post_view($posts);
      ?>
    </div>
  </div>
</div>
